<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\KegiatanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanPengajuanController extends Controller
{
    public function index()
    {
        $cashAccounts = CashAccount::orderBy('name')->get();

        return view('pages.persi.kegiatan.pengajuan', compact('cashAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_date' => ['required', 'date'],
            'activity_name' => ['required', 'string', 'max:255'],
            'budget_amount' => ['required', 'string'],
            'cash_account_id' => ['required', 'exists:cash_accounts,id'],
            'activity_description' => ['nullable', 'string', 'max:4000'],
        ]);

        return DB::transaction(function () use ($request) {
            $amount = (int) preg_replace('/\D+/', '', (string) $request->budget_amount);
            if ($amount <= 0) {
                return back()->withErrors(['budget_amount' => 'Nominal harus lebih dari 0'])->withInput();
            }

            $nextNo = str_pad(((int) (KegiatanRequest::max('id') ?? 0) + 1), 10, '0', STR_PAD_LEFT);

            KegiatanRequest::create([
                'activity_no' => $nextNo,
                'activity_date' => $request->activity_date,
                'activity_name' => $request->activity_name,
                'activity_description' => $request->activity_description,
                'cash_account_id' => $request->cash_account_id,
                'budget_amount' => $amount,
                'status' => 'outstanding',
                'created_by' => auth()->id(),
            ]);

            return redirect()
                ->route('persi.kegiatan.pengajuan')
                ->with('success', 'Pengajuan kegiatan berhasil disimpan.');
        });
    }
}
