<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('phone', 50)->nullable();
            $table->string('national_id', 50)->nullable();
            $table->string('relation', 100)->nullable();
            $table->string('address')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        // A reservation blocks a car for a customer; its deposit is a receipt voucher on customer deposits.
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->unique();
            $table->date('date');
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->foreignId('salesperson_id')->constrained('users')->restrictOnDelete();
            $table->decimal('deposit', 15, 3);
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->date('expires_at')->index();
            $table->string('status', 15)->index(); // ReservationStatus
            $table->foreignId('voucher_id')->nullable()->constrained()->restrictOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        // total = subtotal − discount (the revenue). The customer then owes
        // total − trade_in_value − deposit_applied, settled by `paid` and/or installments.
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->nullable()->unique();
            $table->date('date');
            $table->foreignId('party_id')->constrained()->restrictOnDelete();
            $table->foreignId('salesperson_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('reservation_id')->nullable()->constrained()->restrictOnDelete();
            $table->string('payment_type', 15); // PaymentType
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->decimal('rate', 12, 6);
            $table->decimal('subtotal', 15, 3)->default(0);
            $table->decimal('discount', 15, 3)->default(0);
            $table->decimal('trade_in_value', 15, 3)->default(0);
            $table->decimal('total', 15, 3)->default(0);
            $table->decimal('deposit_applied', 15, 3)->default(0);
            $table->decimal('paid', 15, 3)->default(0);
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('delivered_by')->nullable()->constrained('users')->restrictOnDelete();
            $this->documentColumns($table);

            $table->index(['salesperson_id', 'date']);
        });

        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('sales_invoices')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->decimal('price', 15, 3);
            $table->decimal('discount', 15, 3)->default(0);
            $table->decimal('net', 15, 3)->default(0);
            $table->decimal('net_base', 15, 3)->default(0);
            $table->decimal('cost_snapshot', 15, 3)->nullable(); // vehicles.total_cost frozen on posting
            $table->decimal('commission', 15, 3)->default(0);    // base currency
            $table->foreignId('return_id')->nullable()->constrained('returns')->restrictOnDelete();
            $table->timestamps();

            $table->unique(['invoice_id', 'vehicle_id']);
        });

        // Payments taken at the sale (cash, transfer, down payment); each becomes a receipt voucher on posting.
        Schema::create('sales_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('sales_invoices')->restrictOnDelete();
            $table->foreignId('cashbox_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->foreignId('voucher_id')->nullable()->constrained()->restrictOnDelete();
            $table->timestamps();
        });

        // The customer's car taken in part-exchange. value is in the invoice currency.
        Schema::create('trade_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->decimal('value', 15, 3);
            $table->decimal('value_base', 15, 3)->default(0);
            $table->string('entry_status', 25);
            $table->timestamps();
        });

        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->unique()->constrained()->restrictOnDelete();
            $table->foreignId('guarantor_id')->nullable()->constrained()->restrictOnDelete();
            $table->decimal('down_payment', 15, 3)->default(0);
            $table->decimal('financed_amount', 15, 3);
            $table->unsignedSmallInteger('months');
            $table->decimal('monthly_amount', 15, 3);
            $table->date('start_date');
            $table->timestamps();
        });

        // Amounts in the invoice currency.
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('installment_plans')->restrictOnDelete();
            $table->unsignedSmallInteger('sequence');
            $table->date('due_date');
            $table->decimal('amount', 15, 3);
            $table->decimal('paid_amount', 15, 3)->default(0);
            $table->string('status', 15); // InstallmentStatus
            $table->timestamps();

            $table->index(['due_date', 'status']);
            $table->unique(['plan_id', 'sequence']);
        });

        // How each collection voucher was spread over installments (oldest first).
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')->constrained()->restrictOnDelete();
            $table->foreignId('voucher_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->timestamps();
        });

        // One commission per sold vehicle, in the base currency.
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained()->restrictOnDelete();
            $table->foreignId('sales_invoice_item_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->decimal('amount', 15, 3);
            $table->string('status', 15)->index(); // CommissionStatus
            $table->foreignId('voucher_id')->nullable()->constrained()->restrictOnDelete();
            $table->timestamps();
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->foreign('sale_invoice_id')->references('id')->on('sales_invoices')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', fn (Blueprint $table) => $table->dropForeign(['sale_invoice_id']));
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('installment_payments');
        Schema::dropIfExists('installments');
        Schema::dropIfExists('installment_plans');
        Schema::dropIfExists('trade_ins');
        Schema::dropIfExists('sales_invoice_payments');
        Schema::dropIfExists('sales_invoice_items');
        Schema::dropIfExists('sales_invoices');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('guarantors');
    }
};
