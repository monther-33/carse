<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Vehicles that are not (only) the showroom's (owner's request):
|  - consignment: owned by one or more people, the showroom sells them and earns per the
|    agreement of each car (net price to the owners, a percentage or a fixed commission);
|  - partnership: bought with partners who own a share; the sale is split by shares.
| Owners are parties. What the showroom owes them sits on the "owners payable" control
| account with the party on each line; the per-sale split is kept in vehicle_owner_dues.
*/
return new class extends Migration
{
    public function up(): void
    {
        // One ownership "cycle" of a vehicle: from intake (or partnership purchase) to sale or return.
        Schema::create('vehicle_ownerships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->nullable()->unique();   // consignment intake receipt (CI-YYYY-...)
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->string('kind', 15);                              // consignment | partnership
            $table->decimal('showroom_share', 7, 4)->default(0);     // percent owned by the showroom
            $table->string('earning_mode', 15)->nullable();          // consignment: net_price | percent | fixed
            $table->decimal('earning_amount', 15, 3)->nullable();    // net price to the owners, or fixed commission (LYD)
            $table->decimal('earning_percent', 7, 4)->nullable();    // commission percent
            $table->string('payout', 15);                            // on_sale | on_collection
            $table->string('status', 15)->index();                   // active | sold | returned | closed
            $table->date('received_at');
            $table->date('ended_at')->nullable();
            $table->string('end_reason')->nullable();
            $table->nullableMorphs('source');                        // the purchase invoice of a partnership
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('vehicle_ownership_owners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ownership_id')->constrained('vehicle_ownerships')->restrictOnDelete();
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->decimal('share', 7, 4);                          // percent of the whole car
            $table->decimal('contribution', 15, 3)->default(0);     // partnership: their part of the purchase cost (LYD)
            $table->timestamps();
            $table->unique(['ownership_id', 'party_id']);
        });

        // What each owner is due from each sale (LYD), reversed if the sale is cancelled or returned.
        Schema::create('vehicle_owner_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ownership_id')->constrained('vehicle_ownerships')->restrictOnDelete();
            $table->foreignId('sales_invoice_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->string('payout', 15);                            // copied from the ownership at the sale
            $table->timestamp('reversed_at')->nullable();
            $table->timestamps();
            $table->index(['party_id', 'reversed_at']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreignId('ownership_id')->nullable()->after('sale_invoice_id')->constrained('vehicle_ownerships')->restrictOnDelete();
        });

        Schema::table('sales_invoice_items', function (Blueprint $table) {
            $table->foreignId('ownership_id')->nullable()->constrained('vehicle_ownerships')->restrictOnDelete();
            $table->decimal('showroom_revenue', 15, 3)->nullable();  // LYD: the showroom's part of the item price
        });

        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->json('partners')->nullable();                    // draft partnership: [{party_id, share}]
            $table->string('partner_payout', 15)->nullable();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->string('borne_by', 10)->nullable();              // showroom | owners (vehicles with owners)
        });

        Schema::table('vehicle_costs', function (Blueprint $table) {
            $table->decimal('owners_amount', 15, 3)->default(0);     // part charged to the owners' accounts
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_costs', fn (Blueprint $table) => $table->dropColumn('owners_amount'));
        Schema::table('expenses', fn (Blueprint $table) => $table->dropColumn('borne_by'));
        Schema::table('purchase_invoice_items', fn (Blueprint $table) => $table->dropColumn(['partners', 'partner_payout']));
        Schema::table('sales_invoice_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ownership_id');
            $table->dropColumn('showroom_revenue');
        });
        Schema::table('vehicles', fn (Blueprint $table) => $table->dropConstrainedForeignId('ownership_id'));
        Schema::dropIfExists('vehicle_owner_dues');
        Schema::dropIfExists('vehicle_ownership_owners');
        Schema::dropIfExists('vehicle_ownerships');
    }
};
