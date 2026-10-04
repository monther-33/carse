<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Customers and suppliers share one table; balances live in journal_lines.party_id.
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('type', 10); // PartyType
            $table->string('name');
            $table->string('phone', 50)->nullable();
            $table->string('phone2', 50)->nullable();
            $table->string('national_id', 50)->nullable()->index();
            $table->string('address')->nullable();
            $table->decimal('credit_limit', 15, 3)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
