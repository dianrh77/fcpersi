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
        Schema::create('member_classes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique(); // "1", "2", dst
            $table->string('name', 100)->nullable(); // optional
            $table->unsignedBigInteger('default_dues_amount')->default(0); // rupiah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_classes');
    }
};
