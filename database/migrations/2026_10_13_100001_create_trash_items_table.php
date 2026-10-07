<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| Recycle bin (owner's request, developer only): every record a user deletes, with the parts
| deleted with it (a draft's lines, payments, pending vehicles...), kept as a copy of its row
| so the developer can put it back. One "batch" = one delete operation.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trash_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch')->index();
            $table->unsignedInteger('position');             // deletion order inside the batch
            $table->string('label');                         // what the user deleted (the batch's main record)
            $table->string('model_type');                    // model class
            $table->unsignedBigInteger('model_id');
            $table->boolean('soft')->default(false);         // soft-deleted model: restore() instead of re-inserting
            $table->json('data');                            // the row as stored
            $table->json('extra')->nullable();               // e.g. a role's permissions, a media file copy
            $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('deleted_at');
            $table->foreignId('restored_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('restored_at')->nullable();
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_items');
    }
};
