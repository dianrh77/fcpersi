<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use Illuminate\Http\Request;

class ReportCashflowController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start_date', now()->startOfMonth()->toDateString());
        $end = $request->input('end_date', now()->endOfMonth()->toDateString());
        $cashAccountId = $request->input('cash_account_id');

        $cashAccounts = CashAccount::orderBy('name')->get();

        $query = CashTransaction::with(['cashAccount', 'coaAccount', 'member'])
            ->whereBetween('trx_date', [$start, $end])
            ->orderBy('trx_date')
            ->orderBy('id');

        if (!empty($cashAccountId)) {
            $query->where('cash_account_id', $cashAccountId);
        }

        $rows = $query->get();

        $opening = $this->getOpeningBalance($start, $cashAccountId);

        $balance = $opening;
        $entries = [];
        $totalIn = 0;
        $totalOut = 0;

        foreach ($rows as $t) {
            $isIn = $t->type === 'income';
            $in = $isIn ? (int) $t->amount : 0;
            $out = $isIn ? 0 : (int) $t->amount;

            $balance += $in - $out;
            $totalIn += $in;
            $totalOut += $out;

            $parts = array_filter([
                $t->coaAccount?->name,
                $t->description,
                $t->member?->name ? ('[' . $t->member->name . ']') : null,
            ]);

            $entries[] = [
                'trx_date' => $t->trx_date,
                'uraian' => implode(' - ', $parts) ?: '-',
                'pos' => $t->cashAccount?->name ?? '-',
                'reference_no' => $t->reference_no ?? '-',
                'in' => $in,
                'out' => $out,
                'balance' => $balance,
            ];
        }

        $ending = $opening + $totalIn - $totalOut;

        return view('pages.persi.report.cashflow', compact(
            'cashAccounts',
            'start',
            'end',
            'cashAccountId',
            'opening',
            'totalIn',
            'totalOut',
            'ending',
            'entries'
        ));
    }

    private function getOpeningBalance(string $start, ?string $cashAccountId): int
    {
        if (!empty($cashAccountId)) {
            $acc = CashAccount::find($cashAccountId);
            if (!$acc) {
                return 0;
            }

            $income = (int) CashTransaction::where('cash_account_id', $cashAccountId)
                ->where('type', 'income')
                ->where('trx_date', '<', $start)
                ->sum('amount');

            $expense = (int) CashTransaction::where('cash_account_id', $cashAccountId)
                ->where('type', 'expense')
                ->where('trx_date', '<', $start)
                ->sum('amount');

            return (int) $acc->opening_balance + $income - $expense;
        }

        $opening = (int) CashAccount::sum('opening_balance');

        $income = (int) CashTransaction::where('type', 'income')
            ->where('trx_date', '<', $start)
            ->sum('amount');

        $expense = (int) CashTransaction::where('type', 'expense')
            ->where('trx_date', '<', $start)
            ->sum('amount');

        return $opening + $income - $expense;
    }
}
