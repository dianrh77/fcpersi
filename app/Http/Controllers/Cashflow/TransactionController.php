<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\CashTransaction;
use App\Models\CoaAccount;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $rows = CashTransaction::query()
            ->with(['cashAccount', 'coaAccount', 'member'])
            ->orderByDesc('trx_date')
            ->orderByDesc('id')
            ->limit(5000)
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'trx_date' => $t->trx_date,
                    'type' => $t->type, // income|expense
                    'pos' => $t->cashAccount?->name ?? '-',
                    'coa' => trim(($t->coaAccount?->code ?? '-') . ' - ' . ($t->coaAccount?->name ?? '-')),
                    'member' => $t->member?->name ?? '-',
                    'amount' => (int) $t->amount,
                    'description' => $t->description ?? '-',
                    'reference_no' => $t->reference_no ?? '-',
                ];
            })
            ->values();

        return view('pages.persi.transaksi.index', [
            'transactions' => $rows, // sudah flat
        ]);
    }




    public function createIncome()
    {
        $cashAccounts = CashAccount::orderBy('name')->get();
        $members      = Member::where('is_active', true)->orderBy('name')->get();

        // Ambil parent level 2 (1.1 / 1.2 / 1.3) lalu children-nya (1.1.1 dst)
        $coaGroups = CoaAccount::query()
            ->where('type', 'income')
            ->where('is_active', true)
            ->where('level', 2) // 1.1, 1.2, 1.3
            ->orderBy('code')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('code');
            }])
            ->get();

        return view('pages.persi.transaksi.penerimaan', compact('cashAccounts', 'members', 'coaGroups'));
    }


    public function storeIncome(Request $request)
    {
        $data = $request->validate([
            'trx_date' => ['required', 'date'],
            'cash_account_id' => ['required', 'exists:cash_accounts,id'],
            'member_id' => ['nullable', 'exists:members,id'],
            'coa_account_id' => ['required', 'exists:coa_accounts,id'],
            'amount' => ['required', 'integer', 'min:1'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        // Pastikan COA memang income
        $coa = CoaAccount::findOrFail($data['coa_account_id']);
        if ($coa->type !== 'income') {
            return back()->withErrors(['coa_account_id' => 'COA tidak sesuai (harus Penerimaan).'])->withInput();
        }

        DB::transaction(function () use ($data) {
            CashTransaction::create([
                'trx_date' => $data['trx_date'],
                'type' => 'income',
                'cash_account_id' => $data['cash_account_id'],
                'member_id' => $data['member_id'] ?? null,
                'coa_account_id' => $data['coa_account_id'],
                'amount' => $data['amount'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('persi.trx.index', ['type' => 'income'])
            ->with('success', 'Penerimaan berhasil disimpan.');
    }

    public function createExpense()
    {
        $cashAccounts = CashAccount::orderBy('name')->get();
        $members      = Member::where('is_active', true)->orderBy('name')->get();

        $coaGroups = CoaAccount::query()
            ->where('type', 'expense')
            ->where('is_active', true)
            ->where('level', 2) // 2.1, 2.2, 2.3...
            ->orderBy('code')
            ->with(['children' => fn($q) => $q->where('is_active', true)->orderBy('code')])
            ->get();

        return view('pages.persi.transaksi.pengeluaran', compact('cashAccounts', 'members', 'coaGroups'));
    }

    public function storeExpense(Request $request)
    {
        $data = $request->validate([
            'trx_date' => ['required', 'date'],
            'cash_account_id' => ['required', 'exists:cash_accounts,id'],
            'member_id' => ['nullable', 'exists:members,id'],
            'coa_account_id' => ['required', 'exists:coa_accounts,id'],
            'amount' => ['required', 'integer', 'min:1'],
            'reference_no' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        // Pastikan COA memang expense
        $coa = CoaAccount::findOrFail($data['coa_account_id']);
        if ($coa->type !== 'expense') {
            return back()->withErrors(['coa_account_id' => 'COA tidak sesuai (harus Pengeluaran).'])->withInput();
        }

        // Validasi saldo cukup (simple)
        $cashAccountId = (int) $data['cash_account_id'];
        $balance = $this->getCashAccountBalance($cashAccountId);

        if ($data['amount'] > $balance) {
            return back()->withErrors(['amount' => 'Saldo tidak mencukupi.'])->withInput();
        }

        DB::transaction(function () use ($data) {
            CashTransaction::create([
                'trx_date' => $data['trx_date'],
                'type' => 'expense',
                'cash_account_id' => $data['cash_account_id'],
                'member_id' => $data['member_id'] ?? null,
                'coa_account_id' => $data['coa_account_id'],
                'amount' => $data['amount'],
                'reference_no' => $data['reference_no'] ?? null,
                'description' => $data['description'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()->route('persi.trx.index', ['type' => 'expense'])
            ->with('success', 'Pengeluaran berhasil disimpan.');
    }

    private function getCashAccountBalance(int $cashAccountId): int
    {
        $acc = CashAccount::findOrFail($cashAccountId);

        $income = (int) CashTransaction::where('cash_account_id', $cashAccountId)
            ->where('type', 'income')
            ->sum('amount');

        $expense = (int) CashTransaction::where('cash_account_id', $cashAccountId)
            ->where('type', 'expense')
            ->sum('amount');

        return (int) $acc->opening_balance + $income - $expense;
    }
}
