<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('umk_realizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umk_request_id')->constrained('umk_requests')->cascadeOnDelete();

            $table->date('trx_date');
            $table->enum('type', ['expense', 'income'])->default('expense'); // pengeluaran / penerimaan(pengembalian)
            $table->unsignedBigInteger('amount');
            $table->text('description')->nullable();

            // link ke mutasi kas (agar tombol "lihat mutasi" bisa)
            $table->foreignId('cash_transaction_id')->nullable()->constrained('cash_transactions')->nullOnDelete();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['trx_date', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umk_realizations');
    }
};
