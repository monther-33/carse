<?php

use App\Actions\DeleteDraft;
use App\Actions\Purchases\DeletePurchaseDraft;
use App\Actions\Purchases\SavePurchaseInvoice;
use App\Actions\Sales\DeleteSalesDraft;
use App\Actions\Users\SaveRolePermissions;
use App\Actions\Vouchers\SaveVoucher;
use App\Exceptions\BusinessRuleException;
use App\Livewire\Parties\Index as Parties;
use App\Livewire\System\Trash;
use App\Livewire\Vehicles\Show as VehicleCard;
use App\Models\Party;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\TrashItem;
use App\Models\Vehicle;
use App\Models\Voucher;
use App\Services\Trash\RecycleBin;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->actingAs(userWithRole('developer'));
});

function draftPurchase(string $vin): PurchaseInvoice
{
    return app(SavePurchaseInvoice::class)->handle([
        'date' => today()->toDateString(), 'party_id' => supplier()->id, 'source' => 'supplier',
        'currency_id' => lyd()->id, 'rate' => '1', 'discount' => '0', 'paid' => '0', 'cashbox_id' => null, 'notes' => null,
        'items' => [purchaseLine(['vin' => $vin])],
    ]);
}

it('keeps a deleted draft purchase with its lines and pending car, and puts it all back', function () {
    $invoice = draftPurchase('TRASH00000000001');

    app(DeletePurchaseDraft::class)->handle($invoice);

    expect(PurchaseInvoice::query()->find($invoice->id))->toBeNull()
        ->and(Vehicle::query()->where('vin', 'TRASH00000000001')->exists())->toBeFalse()
        ->and(TrashItem::query()->count())->toBe(3);   // line, car, invoice

    $batch = TrashItem::query()->value('batch');
    expect(app(RecycleBin::class)->restore($batch))->toBe(3);

    $restored = PurchaseInvoice::query()->with('items.vehicle')->findOrFail($invoice->id);
    expect($restored->items->sole()->vehicle->vin)->toBe('TRASH00000000001')
        ->and(TrashItem::query()->whereNull('restored_at')->count())->toBe(0);
});

it('refuses to restore what clashes with a newer record', function () {
    app(DeletePurchaseDraft::class)->handle(draftPurchase('TRASH00000000002'));
    draftPurchase('TRASH00000000002');   // the same VIN on a new draft

    app(RecycleBin::class)->restore(TrashItem::query()->value('batch'));
})->throws(BusinessRuleException::class);

it('keeps a deleted draft sale with its lines', function () {
    $invoice = saveSale(purchaseVehicle());

    app(DeleteSalesDraft::class)->handle($invoice);
    app(RecycleBin::class)->restore(TrashItem::query()->value('batch'));

    expect(SalesInvoice::query()->findOrFail($invoice->id)->items)->toHaveCount(1);
});

it('keeps a deleted draft voucher', function () {
    $voucher = app(SaveVoucher::class)->handle([
        'type' => 'receipt', 'date' => today()->toDateString(), 'cashbox_id' => cashbox('خزينة دينار')->id,
        'account_id' => account('13')->id, 'party_id' => customer()->id, 'amount' => '500', 'description' => 'سند',
    ]);

    app(DeleteDraft::class)->handle($voucher);
    expect(TrashItem::query()->value('label'))->toContain('500');

    app(RecycleBin::class)->restore(TrashItem::query()->value('batch'));
    expect(Voucher::query()->find($voucher->id))->not->toBeNull();
});

it('restores a soft-deleted party and a role with its permissions', function () {
    $party = customer();
    Livewire::test(Parties::class)->call('delete', $party->id);
    expect(Party::query()->find($party->id))->toBeNull();

    $actions = app(SaveRolePermissions::class);
    $role = $actions->create('مراجع');
    $role->syncPermissions(['vehicles.view', 'sales.view']);
    $actions->delete($role);

    foreach (TrashItem::query()->distinct()->pluck('batch') as $batch) {
        app(RecycleBin::class)->restore($batch);
    }

    expect(Party::query()->find($party->id))->not->toBeNull()
        ->and(Role::findByName('مراجع')->permissions->pluck('name')->sort()->values()->all())->toBe(['sales.view', 'vehicles.view']);
});

it('does not keep what a draft loses when it is edited', function () {
    $invoice = draftPurchase('TRASH00000000003');
    app(SavePurchaseInvoice::class)->handle([
        'date' => today()->toDateString(), 'party_id' => $invoice->party_id, 'source' => 'supplier',
        'currency_id' => lyd()->id, 'rate' => '1', 'discount' => '0', 'paid' => '0', 'cashbox_id' => null, 'notes' => null,
        'items' => [purchaseLine()],
    ], $invoice);

    expect(TrashItem::query()->count())->toBe(0);
});

it('is the developer screen only', function () {
    app(DeletePurchaseDraft::class)->handle(draftPurchase('TRASH00000000004'));

    Livewire::test(Trash::class)
        ->assertSee(__('trash.restore'))
        ->call('restore', TrashItem::query()->value('batch'))
        ->assertHasNoErrors()
        ->set('show', 'all')
        ->assertSee(__('trash.restored'));

    $this->actingAs(userWithRole('admin'));
    $this->get(route('system.trash'))->assertForbidden();
});

it('restores a deleted vehicle photo with its file', function () {
    Storage::fake('public');
    $vehicle = purchaseVehicle();
    $media = $vehicle->addMedia(UploadedFile::fake()->image('front.jpg', 640, 480))->toMediaCollection('photos');
    $path = $media->getPath();

    Livewire::test(VehicleCard::class, ['vehicle' => $vehicle])->call('deleteMedia', $media->id);
    expect(file_exists($path))->toBeFalse();

    app(RecycleBin::class)->restore(TrashItem::query()->value('batch'));

    expect($vehicle->refresh()->getMedia('photos'))->toHaveCount(1)
        ->and(file_exists($path))->toBeTrue();
});
