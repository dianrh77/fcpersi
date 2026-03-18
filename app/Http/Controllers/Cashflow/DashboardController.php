<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $year = now()->year;

        // saldo semua account
        $accounts = CashAccount::query()
            ->withSum(['transactions as income_sum' => function ($q) {
                $q->where('type', 'income');
            }], 'amount')
            ->withSum(['transactions as expense_sum' => function ($q) {
                $q->where('type', 'expense');
            }], 'amount')
            ->get()
            ->map(function ($acc) {
                $income  = (int) ($acc->income_sum ?? 0);
                $expense = (int) ($acc->expense_sum ?? 0);
                $acc->balance = (int) $acc->opening_balance + $income - $expense;
                return $acc;
            });

        // contoh ambil kas kecil (type=cash) dan kas besar/bank (type=bank)
        $saldoKasKecil = (int) $accounts->where('type', 'cash')->sum('balance');
        $saldoKasBesar = (int) $accounts->where('type', 'bank')->sum('balance');

        // This month summary
        $startMonth = now()->startOfMonth()->toDateString();
        $endMonth   = now()->endOfMonth()->toDateString();

        $incomeThisMonth = (int) CashTransaction::whereBetween('trx_date', [$startMonth, $endMonth])
            ->where('type', 'income')
            ->sum('amount');

        $expenseThisMonth = (int) CashTransaction::whereBetween('trx_date', [$startMonth, $endMonth])
            ->where('type', 'expense')
            ->sum('amount');

        // Chart by month (tahun berjalan)
        $incomeByMonth = array_fill(0, 12, 0);
        $expenseByMonth = array_fill(0, 12, 0);

        $rows = CashTransaction::selectRaw('MONTH(trx_date) as m, type, SUM(amount) as total')
            ->whereYear('trx_date', $year)
            ->groupBy('m', 'type')
            ->get();

        foreach ($rows as $r) {
            $idx = ((int)$r->m) - 1;
            if ($r->type === 'income') $incomeByMonth[$idx] = (int)$r->total;
            if ($r->type === 'expense') $expenseByMonth[$idx] = (int)$r->total;
        }

        return view('pages.persi.dashboard', compact(
            'saldoKasKecil',
            'saldoKasBesar',
            'incomeThisMonth',
            'expenseThisMonth',
            'incomeByMonth',
            'expenseByMonth'
        ));
    }
}
