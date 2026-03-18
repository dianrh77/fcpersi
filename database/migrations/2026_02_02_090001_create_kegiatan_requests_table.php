<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatan_requests', function (Blueprint $table) {
            $table->id();
            $table->string('activity_no', 30)->unique();
            $table->date('activity_date');
            $table->string('activity_name', 255);
            $table->text('activity_description')->nullable();

            $table->foreignId('cash_account_id')->constrained('cash_accounts')->cascadeOnDelete();
            $table->unsignedBigInteger('budget_amount')->default(0);

            $table->enum('status', ['outstanding', 'closed'])->default('outstanding');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'activity_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_requests');
    }
};
