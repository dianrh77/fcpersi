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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('persi_code', 50)->unique(); // IDPERSI: PJTG.01
            $table->string('name', 255);
            $table->foreignId('member_class_id')->nullable()->constrained('member_classes')->nullOnDelete();
            $table->unsignedBigInteger('dues_amount')->default(0); // IUR REAL (override)
            $table->string('address', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('pic_whatsapp', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
