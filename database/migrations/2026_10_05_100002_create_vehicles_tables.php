<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per physical car (VIN). Costs are in the base currency (LYD) and
        // describe the current stock cycle: total_cost = purchase_cost + extra_cost.
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('vin', 30)->unique();
            $table->string('plate_no', 30)->nullable()->index();
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->foreignId('model_id')->constrained('car_models')->restrictOnDelete();
            $table->string('trim', 100)->nullable();
            $table->unsignedSmallInteger('year');
            $table->foreignId('color_id')->nullable()->constrained()->restrictOnDelete();
            $table->unsignedInteger('mileage')->nullable();
            $table->string('fuel', 15)->nullable();
            $table->string('transmission', 15)->nullable();
            $table->string('condition', 10);
            $table->string('origin', 100)->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('status', 25)->index();
            $table->decimal('purchase_cost', 15, 3)->default(0);
            $table->decimal('extra_cost', 15, 3)->default(0);
            $table->decimal('total_cost', 15, 3)->default(0);
            $table->decimal('asking_price', 15, 3)->nullable();
            $table->decimal('min_price', 15, 3)->nullable();
            $table->unsignedBigInteger('purchase_invoice_id')->nullable(); // FK added with purchase_invoices
            $table->unsignedBigInteger('sale_invoice_id')->nullable();     // FK added in phase 3
            $table->date('received_at')->nullable();
            $table->date('sold_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['brand_id', 'model_id']);
        });

        Schema::create('vehicle_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->string('from_status', 25)->nullable();
            $table->string('to_status', 25);
            $table->foreignId('user_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('note')->nullable();
            $table->nullableMorphs('source');
            $table->foreignId('journal_entry_id')->nullable()->constrained()->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_status_logs');
        Schema::dropIfExists('vehicles');
    }
};
