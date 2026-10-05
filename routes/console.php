<?php

use App\Actions\Alerts\SendDailyAlerts;
use App\Actions\Reservations\EndReservation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('reservations:expire', function (EndReservation $reservations) {
    $count = $reservations->expireOverdue();
    $this->info("Expired reservations: {$count}");
})->purpose('Expire reservations past their expiry date and free their vehicles');

Artisan::command('alerts:daily', function (SendDailyAlerts $alerts) {
    $count = $alerts->handle();
    $this->info("Users notified: {$count}");
})->purpose('Notify users of due installments, expiring reservations and stale vehicles');

// Times are in the application timezone (APP_TIMEZONE).
Schedule::command('reservations:expire')->dailyAt('00:10');
Schedule::command('alerts:daily')->dailyAt('07:00');

// Backups: database dump + uploaded files (config/backup.php), then retention and a health check.
Schedule::command('backup:clean')->dailyAt('01:30');
Schedule::command('backup:run')->dailyAt('02:00');
Schedule::command('backup:monitor')->dailyAt('08:00');
