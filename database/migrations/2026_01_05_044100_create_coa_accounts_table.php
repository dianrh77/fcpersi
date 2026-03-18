<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coa_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // contoh: 1.1.2
            $table->string('name', 255);
            $table->enum('type', ['income', 'expense']);
            $table->unsignedTinyInteger('level'); // 1..n
            $table->foreignId('parent_id')->nullable()->constrained('coa_accounts')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coa_accounts');
    }
};
