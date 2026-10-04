<?php

use App\Actions\Reservations\EndReservation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('reservations:expire', function (EndReservation $reservations) {
    $count = $reservations->expireOverdue();
    $this->info("Expired reservations: {$count}");
})->purpose('Expire reservations past their expiry date and free their vehicles');

Schedule::command('reservations:expire')->dailyAt('00:10');
