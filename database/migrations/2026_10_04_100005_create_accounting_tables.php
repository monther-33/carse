<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('accounts')->restrictOnDelete();
            $table->string('type', 20);    // AccountType
            $table->string('nature', 10);  // AccountNature
            $table->boolean('is_group')->default(false);
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        Schema::create('cashboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('type', 10); // CashboxType
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->foreignId('account_id')->unique()->constrained()->restrictOnDelete();
            $table->string('bank_name')->nullable();
            $table->string('account_number', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });

        // Which cashboxes a treasurer is allowed to see and use.
        Schema::create('cashbox_user', function (Blueprint $table) {
            $table->foreignId('cashbox_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->primary(['cashbox_id', 'user_id']);
        });

        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date')->unique();
            $table->date('end_date');
            $table->boolean('is_closed')->default(false);
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('number', 30)->unique();
            $table->date('date');
            $table->string('description');
            $table->nullableMorphs('source');
            $table->string('status', 15); // DocumentStatus: posted | cancelled
            $table->foreignId('period_id')->constrained('fiscal_periods')->restrictOnDelete();
            $table->foreignId('reversed_by_id')->nullable()->constrained('journal_entries')->restrictOnDelete();
            $table->foreignId('reverses_id')->nullable()->constrained('journal_entries')->restrictOnDelete();
            $table->decimal('total_base', 15, 3);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();

            $table->index('date');
        });

        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained('journal_entries')->restrictOnDelete();
            $table->unsignedSmallInteger('line_no');
            $table->foreignId('account_id')->constrained()->restrictOnDelete();
            // FKs for party_id / vehicle_id are added in phase 2 once their tables exist.
            $table->unsignedBigInteger('party_id')->nullable();
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->decimal('debit', 15, 3)->default(0);
            $table->decimal('credit', 15, 3)->default(0);
            $table->foreignId('currency_id')->constrained()->restrictOnDelete();
            $table->decimal('rate', 12, 6);
            $table->decimal('debit_base', 15, 3)->default(0);
            $table->decimal('credit_base', 15, 3)->default(0);
            $table->string('memo')->nullable();
            $table->timestamps();

            $table->index(['account_id', 'entry_id']);
            $table->index('party_id');
            $table->index('vehicle_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('journal_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('fiscal_periods');
        Schema::dropIfExists('cashbox_user');
        Schema::dropIfExists('cashboxes');
        Schema::dropIfExists('accounts');
    }
};
