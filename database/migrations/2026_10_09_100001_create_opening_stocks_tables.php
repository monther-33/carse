<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vehicles already in stock when the system goes live (imported from Excel).
        // Posting: Dr inventory / in-transit per vehicle, Cr the "opening balances" account.
        Schema::create('opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->nullable()->unique();
            $table->date('date');
            $table->string('description');
            $table->string('status', 15)->index();
            $table->foreignId('journal_entry_id')->nullable()->constrained()->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('opening_stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opening_stock_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->string('entry_status', 25); // VehicleStatus::entryStatuses()
            $table->decimal('cost', 15, 3);     // base currency
            $table->date('received_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opening_stock_items');
        Schema::dropIfExists('opening_stocks');
    }
};
