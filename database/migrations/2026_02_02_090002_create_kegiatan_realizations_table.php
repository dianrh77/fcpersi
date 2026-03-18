<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kegiatan_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_request_id')->constrained('kegiatan_requests')->cascadeOnDelete();

            $table->date('trx_date');
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->foreignId('coa_account_id')->constrained('coa_accounts')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->text('description')->nullable();

            $table->foreignId('cash_transaction_id')->nullable()->constrained('cash_transactions')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['trx_date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan_realizations');
    }
};
