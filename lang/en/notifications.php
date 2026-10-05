<?php

return [
    'none' => 'No notifications.',
    'view_all' => 'All notifications',
    'mark_read' => 'Mark read',
    'mark_all_read' => 'Mark all as read',
    'all_read' => 'All notifications marked as read.',
    'alerts' => [
        'installments_overdue' => ':count overdue installment(s) totalling :amount',
        'installments_week' => ':count installment(s) due within :days days totalling :amount',
        'reservations_expiring' => ':count reservation(s) ending within :days days',
        'stale_critical' => ':count vehicle(s) in stock for more than :days days',
        'stale_warning' => ':count vehicle(s) in stock for more than :days days',
    ],
    'backup' => [
        'failed' => 'Backup failed: :message',
        'unhealthy' => 'Backups are unhealthy: :message',
        'cleanup_failed' => 'Cleaning old backups failed: :message',
    ],
];
