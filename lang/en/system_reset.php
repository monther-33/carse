<?php

return [
    'warning' => 'Warning: everything in the ":database" database (vehicles, invoices, vouchers, entries, parties, users and settings) and every photo and attachment will be deleted, and the system will be as just installed. Backups are kept.',
    'type_name' => 'To confirm, type the database name (:database)',
    'backup_first' => 'Take a full backup before resetting?',
    'backup_failed' => 'The backup failed. Reset anyway, without a backup?',
    'cancelled' => 'Reset cancelled, nothing changed.',
    'done' => 'The system was reset. Sign in as admin with ADMIN_PASSWORD and as developer with DEVELOPER_PASSWORD, then change both.',
    'demo_refused' => 'The demo data is never seeded on a production server (APP_ENV=production).',
];
