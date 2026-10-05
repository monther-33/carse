<?php

namespace App\Reports\Admin;

use App\Models\User;
use App\Reports\Report;
use Spatie\Activitylog\Models\Activity;

/**
 * Audit trail: every create / update / delete / status change, plus logins and failed logins.
 */
class AuditLogReport extends Report
{
    public static function key(): string
    {
        return 'audit_log';
    }

    public function group(): string
    {
        return 'admin';
    }

    public function permissions(): array
    {
        return ['audit.view'];
    }

    public function filters(): array
    {
        return ['from', 'to', 'user_id', 'log_name'];
    }

    public function options(): array
    {
        return ['log_name' => Activity::query()->distinct()->orderBy('log_name')->pluck('log_name', 'log_name')->all()];
    }

    public function columns(User $user, array $f): array
    {
        return [
            'time' => ['label' => __('reports.time'), 'type' => 'text'],
            'user' => ['label' => __('app.fields.user'), 'type' => 'text'],
            'subject' => ['label' => __('reports.subject'), 'type' => 'text'],
            'event' => ['label' => __('reports.event'), 'type' => 'text'],
            'changes' => ['label' => __('reports.changes'), 'type' => 'text'],
        ];
    }

    public function rows(User $user, array $f): array
    {
        return Activity::query()
            ->with('causer')
            ->whereDate('created_at', '>=', $f['from'])
            ->whereDate('created_at', '<=', $f['to'])
            ->when(! empty($f['user_id']), fn ($q) => $q->where('causer_id', $f['user_id'])->where('causer_type', (new User)->getMorphClass()))
            ->when(! empty($f['log_name']), fn ($q) => $q->where('log_name', $f['log_name']))
            ->latest('id')
            ->limit(2000)
            ->get()
            ->map(fn (Activity $a) => [
                'time' => $a->created_at?->format('Y-m-d H:i'),
                'user' => $a->causer?->getAttribute('name'),
                'subject' => $a->log_name.($a->subject_id ? ' #'.$a->subject_id : ''),
                'event' => $a->event ?? $a->description,
                'changes' => $this->summary($a),
            ])->all();
    }

    private function summary(Activity $activity): string
    {
        $new = (array) ($activity->properties['attributes'] ?? []);
        $old = (array) ($activity->properties['old'] ?? []);
        $parts = [];

        foreach ($new as $key => $value) {
            if (is_array($value) || $key === 'updated_at') {
                continue;
            }
            $parts[] = array_key_exists($key, $old) ? "{$key}: {$this->scalar($old[$key])} → {$this->scalar($value)}" : "{$key}: {$this->scalar($value)}";
        }

        foreach (['ip', 'email'] as $key) {
            if (isset($activity->properties[$key])) {
                $parts[] = "{$key}: {$activity->properties[$key]}";
            }
        }

        return mb_strimwidth(implode('، ', $parts), 0, 300, '…');
    }

    private function scalar(mixed $value): string
    {
        return is_scalar($value) || $value === null ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
