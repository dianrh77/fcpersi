<?php

namespace App\Http\Controllers\Cashflow;

use App\Http\Controllers\Controller;
use App\Models\KegiatanRequest;
use Illuminate\Http\Request;

class KegiatanRekapController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'outstanding');
        $q = $request->get('q');

        $kegiatans = KegiatanRequest::query()
            ->with('realizations')
            ->when($status, fn($x) => $x->where('status', $status))
            ->when($q, function ($x) use ($q) {
                $x->where(function ($z) use ($q) {
                    $z->where('activity_no', 'like', "%$q%")
                        ->orWhere('activity_name', 'like', "%$q%");
                });
            })
            ->orderByDesc('activity_date')
            ->get();

        $count = $kegiatans->count();
        $sumOutstanding = $kegiatans->sum(fn($k) => $k->outstanding);

        return view('pages.persi.kegiatan.rekap', compact('kegiatans', 'status', 'q', 'count', 'sumOutstanding'));
    }
}
