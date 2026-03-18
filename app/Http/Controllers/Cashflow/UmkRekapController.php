<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\UmkRequest;
use Illuminate\Http\Request;

class UmkRekapController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'outstanding');
        $q = $request->get('q');

        $umks = UmkRequest::query()
            ->with('realizations')
            ->when($status, fn($x) => $x->where('status', $status))
            ->when($q, function ($x) use ($q) {
                $x->where(function ($z) use ($q) {
                    $z->where('request_no', 'like', "%$q%")
                        ->orWhere('activity_name', 'like', "%$q%");
                });
            })
            ->orderByDesc('request_date')
            ->get();

        $count = $umks->count();
        $sumOutstanding = $umks->sum(fn($u) => $u->outstanding);

        return view('pages.persi.umk.rekap', compact('umks', 'status', 'q', 'count', 'sumOutstanding'));
    }
}
