<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Consignment car: the showroom's commission is chosen on the sale (owner's request).
| null = as agreed on intake; 0 = no commission (the whole price to the owners);
| any other amount = that commission in LYD for this sale.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_invoice_items', function (Blueprint $table) {
            $table->decimal('showroom_commission', 15, 3)->nullable()->after('showroom_revenue');
        });
    }

    public function down(): void
    {
        Schema::table('sales_invoice_items', fn (Blueprint $table) => $table->dropColumn('showroom_commission'));
    }
};
