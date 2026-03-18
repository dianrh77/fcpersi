<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umk_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_no', 30)->unique(); // 0000000001
            $table->date('request_date');
            $table->string('activity_name', 255);
            $table->text('activity_description')->nullable();

            // Pos sumber dana UMK
            $table->foreignId('cash_account_id')->constrained('cash_accounts')->cascadeOnDelete();

            // Nominal UMK global
            $table->unsignedBigInteger('amount');

            // status
            $table->enum('status', ['outstanding', 'closed'])->default('outstanding');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'request_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umk_requests');
    }
};
