<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')->after('id')->constrained('branches')->restrictOnDelete();
            $table->boolean('is_active')->default(true)->after('password');
            $table->decimal('max_discount', 15, 3)->default(0)->after('is_active');
            $table->foreignId('created_by')->nullable()->after('remember_token')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')->constrained('users')->restrictOnDelete();
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by');
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn(['is_active', 'max_discount']);
        });
    }
};
