<?php

namespace App\Notifications;

use App\Support\Money;
use Illuminate\Notifications\Notification;

/**
 * The daily digest shown under the bell. Only figures are stored; the text is rendered in
 * the reader's language when displayed.
 */
class DailyAlerts extends Notification
{
    /**
     * @param  list<array{key: string, count: int, amount?: string, days?: int, route: string}>  $sections
     */
    public function __construct(public readonly array $sections) {}

    /** @return list<string> */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /** @return array{sections: list<array{key: string, count: int, amount?: string, days?: int, route: string}>} */
    public function toArray(object $notifiable): array
    {
        return ['sections' => $this->sections];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<array{text: string, url: string, color: string}>
     */
    public static function lines(array $data): array
    {
        $texts = [
            'installments_overdue' => 'notifications.alerts.installments_overdue',
            'installments_week' => 'notifications.alerts.installments_week',
            'reservations_expiring' => 'notifications.alerts.reservations_expiring',
            'stale_critical' => 'notifications.alerts.stale_critical',
            'stale_warning' => 'notifications.alerts.stale_warning',
        ];
        $colors = ['installments_overdue' => 'red', 'stale_critical' => 'red'];

        $lines = [];
        foreach ($data['sections'] ?? [] as $section) {
            if (! isset($texts[$section['key']])) {
                continue;
            }

            $lines[] = [
                'text' => __($texts[$section['key']], [
                    'count' => $section['count'],
                    'amount' => isset($section['amount']) ? Money::format($section['amount']) : '',
                    'days' => $section['days'] ?? '',
                ]),
                'url' => route($section['route']),
                'color' => $colors[$section['key']] ?? 'yellow',
            ];
        }

        return $lines;
    }
}
