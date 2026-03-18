<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\CashAccount;
use App\Models\UmkRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmkPengajuanController extends Controller
{
    public function index()
    {
        $cashAccounts = CashAccount::orderBy('name')->get();

        return view('pages.persi.umk.pengajuan', compact('cashAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'request_date' => ['required', 'date'],
            'activity_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'string'],
            'cash_account_id' => ['required', 'exists:cash_accounts,id'],
            'activity_description' => ['nullable', 'string', 'max:4000'],
        ]);

        return DB::transaction(function () use ($request) {
            $amount = (int) preg_replace('/\D+/', '', (string) $request->amount);
            if ($amount <= 0) {
                return back()->withErrors(['amount' => 'Nominal UMK harus lebih dari 0'])->withInput();
            }

            $nextNo = str_pad(((int) (UmkRequest::max('id') ?? 0) + 1), 10, '0', STR_PAD_LEFT);

            UmkRequest::create([
                'request_no' => $nextNo,
                'request_date' => $request->request_date,
                'activity_name' => $request->activity_name,
                'activity_description' => $request->activity_description,
                'cash_account_id' => $request->cash_account_id,
                'amount' => $amount,
                'status' => 'outstanding',
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('persi.umk.pengajuan')->with('success', 'Pengajuan UMK berhasil disimpan.');
        });
    }
}
