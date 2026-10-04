<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Deposits received and refunded are read from the vouchers referencing the reservation;
        // a forfeiture has no voucher (no cash moves), so its amount is kept here.
        Schema::table('reservations', function (Blueprint $table) {
            $table->decimal('forfeited_amount', 15, 3)->default(0)->after('deposit');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('forfeited_amount');
        });
    }
};
