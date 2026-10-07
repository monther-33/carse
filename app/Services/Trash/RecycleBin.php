<?php

namespace App\Services\Trash;

use App\Exceptions\BusinessRuleException;
use App\Models\Account;
use App\Models\Expense;
use App\Models\ManualJournal;
use App\Models\OpeningStock;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\TrashItem;
use App\Models\Voucher;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Recycle bin (owner's request, seen and restored by the developer only).
 *
 * A user delete runs inside keep(): every Eloquent model deleted meanwhile (the record and the
 * parts deleted with it) is copied to trash_items as one batch, in deletion order. Restoring a
 * batch puts the rows back in reverse order (parents before their lines) with their original
 * ids; soft-deleted models are simply restored. Deletes outside keep() (rebuilding a draft's
 * lines on save, system clean-ups) are not kept.
 *
 * Posted documents are never deleted (they are cancelled with a reversing entry), so they
 * never reach the bin.
 */
class RecycleBin
{
    private ?string $batch = null;

    private string $label = '';

    private int $position = 0;

    /**
     * Runs a delete operation, keeping a copy of everything it deletes. Nested calls join the
     * outer batch.
     *
     * @template T
     *
     * @param  Closure(): T  $delete
     * @return T
     */
    public function keep(Model $root, Closure $delete): mixed
    {
        if ($this->batch !== null) {
            return $delete();
        }

        $this->batch = (string) Str::uuid();
        $this->label = $this->describe($root);
        $this->position = 0;

        try {
            return DB::transaction($delete);
        } finally {
            $this->batch = null;
        }
    }

    /** Called for every Eloquent "deleting" event; keeps the row when a keep() batch is open. */
    public function record(Model $model): void
    {
        if ($this->batch === null || $model instanceof TrashItem) {
            return;
        }

        $soft = in_array(SoftDeletes::class, class_uses_recursive($model), true)
            && ! $model->isForceDeleting(); // @phpstan-ignore method.notFound

        TrashItem::query()->create([
            'batch' => $this->batch,
            'position' => ++$this->position,
            'label' => $this->label,
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'soft' => $soft,
            'data' => $model->getAttributes(),
            'extra' => $this->extra($model),
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
    }

    /**
     * Puts a whole batch back. Refused if any of its rows exists again or clashes with a newer
     * record (e.g. a car with the same VIN was added since).
     *
     * @return int rows restored
     */
    public function restore(string $batch): int
    {
        return DB::transaction(function () use ($batch) {
            $items = TrashItem::query()->where('batch', $batch)->whereNull('restored_at')
                ->orderByDesc('position')->lockForUpdate()->get();
            if ($items->isEmpty()) {
                throw BusinessRuleException::make('trash.errors.nothing');
            }

            foreach ($items as $item) {
                $this->restoreItem($item);
            }

            TrashItem::query()->whereKey($items->modelKeys())->update(['restored_by' => Auth::id(), 'restored_at' => now()]);
            app(PermissionRegistrar::class)->forgetCachedPermissions();

            activity('System')->causedBy(Auth::user())->event('trash_restored')
                ->withProperties(['batch' => $batch, 'label' => $items->first()->label, 'rows' => $items->count()])
                ->log('trash_restored');

            return $items->count();
        });
    }

    private function restoreItem(TrashItem $item): void
    {
        /** @var Model $model */
        $model = new ($item->model_type);

        if ($item->soft) {
            $existing = $model->newQuery()->withTrashed()->find($item->model_id); // @phpstan-ignore method.notFound
            $existing?->restore();

            return;
        }

        if ($model->newQuery()->whereKey($item->model_id)->exists()) {
            throw BusinessRuleException::make('trash.errors.exists', ['label' => $item->label]);
        }

        try {
            DB::table($model->getTable())->insert($item->data);
        } catch (QueryException) {
            throw BusinessRuleException::make('trash.errors.conflict', ['label' => $item->label]);
        }

        if ($model instanceof Role && ! empty($item->extra['permissions'])) {
            Role::query()->findOrFail($item->model_id)->syncPermissions($item->extra['permissions']);
        }
        if ($model instanceof Media && ! empty($item->extra['copy']) && File::isDirectory($item->extra['copy'])) {
            File::copyDirectory($item->extra['copy'], dirname(Media::query()->findOrFail($item->model_id)->getPath()));
        }
    }

    /**
     * What has to be kept besides the row: a role's permissions (their pivot rows go with it),
     * a media item's files (the library deletes them from disk).
     *
     * @return array<string, mixed>|null
     */
    private function extra(Model $model): ?array
    {
        if ($model instanceof Role) {
            // Load them before deleting: the permission library detaches them in its own "deleting" listener.
            return ['permissions' => $model->relationLoaded('permissions') ? $model->permissions->pluck('name')->all() : []];
        }

        if ($model instanceof Media) {
            $dir = dirname($model->getPath());
            if (! File::isDirectory($dir)) {
                return null;
            }
            $copy = storage_path('app/private/trash/'.$this->batch.'/media-'.$model->getKey());
            File::copyDirectory($dir, $copy);

            return ['copy' => $copy];
        }

        return null;
    }

    /** A readable name for what the user deleted. */
    public function describe(Model $model): string
    {
        $ref = method_exists($model, 'displayNumber') ? $model->displayNumber() : '#'.$model->getKey();

        return match (true) {
            $model instanceof SalesInvoice => __('trash.labels.sales', ['ref' => $ref, 'party' => $model->party()->withTrashed()->value('name')]),
            $model instanceof PurchaseInvoice => __('trash.labels.purchase', ['ref' => $ref, 'party' => $model->party()->withTrashed()->value('name')]),
            $model instanceof Voucher => __('trash.labels.voucher', ['type' => $model->type->label(), 'ref' => $ref, 'amount' => $model->amount]),
            $model instanceof Expense => __('trash.labels.expense', ['ref' => $ref, 'text' => $model->description]),
            $model instanceof ManualJournal => __('trash.labels.journal', ['ref' => $ref, 'text' => $model->description]),
            $model instanceof OpeningStock => __('trash.labels.opening_stock', ['ref' => $ref]),
            $model instanceof Account => __('trash.labels.account', ['name' => $model->code.' '.$model->name]),
            $model instanceof Party => __('trash.labels.party', ['name' => $model->name]),
            $model instanceof Role => __('trash.labels.role', ['name' => $model->name]),
            $model instanceof Media => __('trash.labels.media', ['name' => $model->file_name]),
            default => __('trash.labels.record', ['name' => (string) ($model->getAttribute('name') ?? class_basename($model).' #'.$model->getKey())]),
        };
    }
}
