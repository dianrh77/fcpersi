<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{CashAccount, CashTransaction, Member, CoaAccount};

class CashSeeder extends Seeder
{
    public function run(): void
    {
        $bank = CashAccount::create([
            'name' => 'Bank Jateng',
            'type' => 'bank',
            'opening_balance' => 0,
        ], [
            'name' => 'Kas Kecil',
            'type' => 'cash',
            'opening_balance' => 0,
        ]);

        $member = Member::first();
        $coaIncome = CoaAccount::where('code', '1.1')->first();
        $coaExpense = CoaAccount::where('code', '2.1')->first();

        // Penerimaan iuran
        CashTransaction::create([
            'trx_date' => now(),
            'type' => 'income',
            'cash_account_id' => $bank->id,
            'member_id' => $member->id,
            'coa_account_id' => $coaIncome->id,
            'amount' => 5000000,
            'description' => 'Iuran anggota',
        ]);

        // Pengeluaran
        CashTransaction::create([
            'trx_date' => now(),
            'type' => 'expense',
            'cash_account_id' => $bank->id,
            'coa_account_id' => $coaExpense->id,
            'amount' => 1200000,
            'description' => 'Biaya operasional',
        ]);
    }
}
