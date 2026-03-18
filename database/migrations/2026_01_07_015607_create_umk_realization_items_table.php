<?php

// database/migrations/xxxx_xx_xx_create_umk_realization_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umk_realization_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umk_realization_id')->constrained('umk_realizations')->cascadeOnDelete();

            $table->foreignId('coa_account_id')->constrained('coa_accounts')->cascadeOnDelete();
            $table->string('item_name', 255)->nullable();
            $table->unsignedBigInteger('amount');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umk_realization_items');
    }
};
