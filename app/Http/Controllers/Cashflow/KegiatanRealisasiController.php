<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\CoaAccount;
use App\Models\KegiatanRealization;
use App\Models\KegiatanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanRealisasiController extends Controller
{
    public function index()
    {
        $kegiatans = KegiatanRequest::with('cashAccount')
            ->orderByDesc('activity_date')
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'activity_no' => $k->activity_no,
                    'activity_date' => $k->activity_date?->format('Y-m-d') ?? (string) $k->activity_date,
                    'activity_name' => $k->activity_name,
                    'pos' => $k->cashAccount->name ?? '-',
                    'budget_amount' => (int) $k->budget_amount,
                    'status' => $k->status ?? 'outstanding',
                ];
            });

        return view('pages.persi.kegiatan.realisasi.index', compact('kegiatans'));
    }

    public function create(KegiatanRequest $kegiatan)
    {
        $kegiatan->load(['cashAccount', 'realizations' => function ($q) {
            $q->orderByDesc('trx_date');
        }]);

        $coaAccounts = CoaAccount::orderBy('code')->get();

        return view('pages.persi.kegiatan.realisasi.create', compact('kegiatan', 'coaAccounts'));
    }

    public function print(KegiatanRequest $kegiatan)
    {
        $kegiatan->load(['cashAccount', 'realizations' => function ($q) {
            $q->orderBy('trx_date');
        }]);

        return view('pages.persi.kegiatan.realisasi.print', compact('kegiatan'));
    }

    public function store(Request $request, KegiatanRequest $kegiatan)
    {
        if ($kegiatan->status === 'closed') {
            abort(403, 'Kegiatan sudah closed.');
        }

        $request->validate([
            'trx_date' => ['required', 'date'],
            'type' => ['required', 'in:income,expense'],
            'coa_account_id' => ['required', 'exists:coa_accounts,id'],
            'amount' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        return DB::transaction(function () use ($request, $kegiatan) {
            $amount = (int) preg_replace('/\D+/', '', (string) $request->amount);
            if ($amount <= 0) {
                return back()->withErrors(['amount' => 'Nominal harus lebih dari 0'])->withInput();
            }

            $coaId = (int) $request->coa_account_id;

            $cashTrx = CashTransaction::create([
                'trx_date' => $request->trx_date,
                'type' => $request->type,
                'cash_account_id' => $kegiatan->cash_account_id,
                'coa_account_id' => $coaId,
                'amount' => $amount,
                'reference_no' => 'KEG-' . $kegiatan->activity_no,
                'description' => 'Realisasi Kegiatan: ' . ($request->description ?? '-'),
                'created_by' => auth()->id(),
            ]);

            KegiatanRealization::create([
                'kegiatan_request_id' => $kegiatan->id,
                'trx_date' => $request->trx_date,
                'type' => $request->type,
                'coa_account_id' => $coaId,
                'amount' => $amount,
                'description' => $request->description,
                'cash_transaction_id' => $cashTrx->id,
                'created_by' => auth()->id(),
            ]);

            $kegiatan->refresh();
            if ($kegiatan->outstanding <= 0) {
                $kegiatan->update(['status' => 'closed']);
            }

            return redirect()
                ->route('persi.kegiatan.realisasi.create', $kegiatan->id)
                ->with('success', 'Realisasi berhasil disimpan.');
        });
    }

    public function close(KegiatanRequest $kegiatan)
    {
        $kegiatan->update(['status' => 'closed']);

        return redirect()
            ->route('persi.kegiatan.realisasi.create', $kegiatan->id)
            ->with('success', 'Kegiatan berhasil di-closing.');
    }
}
