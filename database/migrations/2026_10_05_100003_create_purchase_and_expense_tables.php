<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns every approvable financial document carries.
     */
    private function documentColumns(Blueprint $table): void
    {
        $table->string('status', 15)->index(); // DocumentStatus
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
    }

    public function up(): void
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->nullable()->unique(); // assigned on posting
            $table->date('date');
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->string('source', 15); // PurchaseSource
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->decimal('rate', 12, 6);
            $table->decimal('subtotal', 15, 3)->default(0);
            $table->decimal('discount', 15, 3)->default(0);
            $table->decimal('total', 15, 3)->default(0);
            $table->decimal('paid', 15, 3)->default(0);
            $table->foreignId('cashbox_id')->nullable()->constrained()->restrictOnDelete();
            $this->documentColumns($table);
        });

        // Amounts in the invoice currency; cost_base is the vehicle's purchase cost in LYD.
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('purchase_invoices')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->string('entry_status', 25); // VehicleStatus the car enters stock with
            $table->decimal('price', 15, 3);
            $table->decimal('discount', 15, 3)->default(0);
            $table->decimal('net', 15, 3)->default(0);
            $table->decimal('cost_base', 15, 3)->default(0);
            $table->unsignedBigInteger('return_id')->nullable(); // FK added below
            $table->timestamps();

            $table->unique(['invoice_id', 'vehicle_id']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices')->restrictOnDelete();
        });

        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('account_id')->constrained()->restrictOnDelete();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // Paid from one cashbox, in that cashbox's currency. vehicle_id set = capitalised on the car.
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->nullable()->unique();
            $table->date('date');
            $table->foreignId('category_id')->constrained('expense_categories')->restrictOnDelete();
            $table->foreignId('cashbox_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->decimal('rate', 12, 6);
            $table->decimal('amount_base', 15, 3)->default(0);
            $table->foreignId('vehicle_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('description');
            $table->unsignedTinyInteger('recurs_every_months')->nullable();
            $table->date('next_due_date')->nullable()->index();
            $this->documentColumns($table);
        });

        // Log of costs capitalised on a vehicle (negative rows undo a cancelled expense).
        Schema::create('vehicle_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('expense_id')->nullable()->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->string('description');
            $table->boolean('to_cost_of_sales')->default(false); // posted after the sale, straight to COGS
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        // Receipts, payments and cashbox transfers. Currency is always the cashbox currency.
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('type', 10); // VoucherType
            $table->string('number', 30)->nullable();
            $table->date('date');
            $table->foreignId('party_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('cashbox_id')->constrained()->restrictOnDelete();
            $table->foreignId('to_cashbox_id')->nullable()->constrained('cashboxes')->restrictOnDelete();
            $table->foreignId('account_id')->nullable()->constrained()->restrictOnDelete(); // counter account
            $table->decimal('amount', 15, 3);
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->decimal('rate', 12, 6);
            $table->decimal('amount_base', 15, 3)->default(0);
            $table->string('description');
            $table->nullableMorphs('reference');
            $this->documentColumns($table);

            $table->unique(['type', 'number']);
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('type', 10); // ReturnType
            $table->string('number', 30)->nullable();
            $table->date('date');
            $table->string('invoice_type');
            $table->unsignedBigInteger('invoice_id');
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);       // invoice currency
            $table->decimal('amount_base', 15, 3);
            $table->string('reason');
            $this->documentColumns($table);

            $table->unique(['type', 'number']);
            $table->index(['invoice_type', 'invoice_id']);
        });

        Schema::table('purchase_invoice_items', function (Blueprint $table) {
            $table->foreign('return_id')->references('id')->on('returns')->restrictOnDelete();
        });

        Schema::table('journal_lines', function (Blueprint $table) {
            $table->foreign('party_id')->references('id')->on('parties')->restrictOnDelete();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('journal_lines', function (Blueprint $table) {
            $table->dropForeign(['party_id']);
            $table->dropForeign(['vehicle_id']);
        });
        Schema::table('purchase_invoice_items', fn (Blueprint $table) => $table->dropForeign(['return_id']));
        Schema::dropIfExists('returns');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('vehicle_costs');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
        Schema::table('vehicles', fn (Blueprint $table) => $table->dropForeign(['purchase_invoice_id']));
        Schema::dropIfExists('purchase_invoice_items');
        Schema::dropIfExists('purchase_invoices');
    }
};
