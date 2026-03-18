<?php

namespace App\Http\Controllers\Cashflow;

use App\Models\CoaAccount;
use App\Models\UmkRequest;
use Illuminate\Http\Request;
use App\Models\UmkRealization;
use App\Models\CashTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UmkRealisasiController extends Controller
{
    public function index()
    {
        $umks = UmkRequest::with('cashAccount')
            ->orderByDesc('request_date')
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'request_no' => $u->request_no,
                    'request_date' => $u->request_date?->format('Y-m-d') ?? (string)$u->request_date,
                    'activity_name' => $u->activity_name,
                    'pos' => $u->cashAccount->name ?? '-',
                    'amount' => (int)$u->amount,
                    'status' => $u->status ?? 'outstanding',
                ];
            });

        return view('pages.persi.umk.realisasi.index', compact('umks'));
    }


    public function search(Request $request)
    {
        $request->validate([
            'request_no' => ['required', 'string'],
        ]);

        $umk = UmkRequest::with(['cashAccount', 'realizations'])
            ->where('request_no', $request->request_no)
            ->first();

        return view('pages.persi.umk.realisasi', compact('umk'))
            ->with('success', $umk ? null : 'No UMK tidak ditemukan.');
    }

    public function store(Request $request, UmkRequest $umk)
    {
        if ($umk->status === 'closed') abort(403, 'UMK sudah closed.');

        $request->validate([
            'trx_date' => ['required', 'date'],
            'type' => ['required', 'in:expense,income'],
            'coa_account_id' => ['required', 'exists:coa_accounts,id'], // ✅ pilih COA
            'amount' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        return DB::transaction(function () use ($request, $umk) {
            $amount = (int) preg_replace('/\D+/', '', (string) $request->amount);
            if ($amount <= 0) {
                return back()->withErrors(['amount' => 'Nominal harus lebih dari 0'])->withInput();
            }

            $coaId = (int) $request->coa_account_id;

            // buat mutasi kas
            $cashTrx = CashTransaction::create([
                'trx_date' => $request->trx_date,
                'type' => $request->type,
                'cash_account_id' => $umk->cash_account_id,
                'coa_account_id' => $coaId,
                'amount' => $amount,
                'reference_no' => 'UMK-' . $umk->request_no,
                'description' => 'Realisasi UMK: ' . ($request->description ?? '-'),
                'created_by' => auth()->id(),
            ]);

            UmkRealization::create([
                'umk_request_id' => $umk->id,
                'trx_date' => $request->trx_date,
                'type' => $request->type,
                'amount' => $amount,
                'description' => $request->description,
                'cash_transaction_id' => $cashTrx->id,
                'created_by' => auth()->id(),
            ]);

            $umk->refresh();
            if ($umk->outstanding <= 0) {
                $umk->update(['status' => 'closed']);
            }

            return redirect()
                ->route('persi.umk.realisasi.create', $umk->id)
                ->with('success', 'Realisasi berhasil disimpan.');
        });
    }


    public function close(UmkRequest $umk)
    {
        $umk->update(['status' => 'closed']);

        return redirect()
            ->route('persi.umk.realisasi.create', $umk->id)
            ->with('success', 'UMK berhasil di-closing.');
    }


    public function create(UmkRequest $umk)
    {
        $umk->load(['cashAccount', 'realizations' => function ($q) {
            $q->orderByDesc('trx_date');
        }]);

        // ✅ ambil COA untuk dipilih
        $coaAccounts = CoaAccount::orderBy('code')->get();

        return view('pages.persi.umk.realisasi.create', compact('umk', 'coaAccounts'));
    }
}
