<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Realisasi Kegiatan</title>
    <style>
        body { font-family: "Times New Roman", Times, serif; margin: 24px; color: #111; }
        .title { text-align: center; font-weight: 700; font-size: 16px; }
        .subtitle { text-align: center; font-size: 12px; margin-top: 2px; }
        .meta { margin-top: 16px; font-size: 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px 16px; }
        .hr { margin: 12px 0; border-top: 1px solid #000; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px 8px; }
        th { background: #f0f0f0; text-align: left; }
        .text-right { text-align: right; }
        .sign { margin-top: 32px; display: grid; grid-template-columns: 1fr 1fr; gap: 24px; font-size: 12px; }
        .sign .box { text-align: center; }
        .sign .name { margin-top: 48px; }
        @media print { body { margin: 12mm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="title">LAPORAN REALISASI KEGIATAN</div>
    <div class="subtitle">PERHIMPUNAN RUMAH SAKIT SELURUH INDONESIA (PERSI) DAERAH JAWA TENGAH</div>

    <div class="meta">
        <div><strong>No. Kegiatan:</strong> {{ $kegiatan->activity_no ?? '-' }}</div>
        <div><strong>Tanggal:</strong> {{ $kegiatan?->activity_date ? \Carbon\Carbon::parse($kegiatan->activity_date)->format('d/m/Y') : '-' }}</div>
        <div><strong>Nama Kegiatan:</strong> {{ $kegiatan->activity_name ?? '-' }}</div>
        <div><strong>Pos:</strong> {{ $kegiatan->cashAccount->name ?? '-' }}</div>
        <div><strong>Anggaran:</strong> Rp {{ number_format((int) ($kegiatan->budget_amount ?? 0), 0, ',', '.') }}</div>
        <div><strong>Outstanding:</strong> Rp {{ number_format((int) ($kegiatan->outstanding ?? 0), 0, ',', '.') }}</div>
    </div>

    <div class="hr"></div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>COA</th>
                <th class="text-right">Nominal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @if ($kegiatan && $kegiatan->realizations && $kegiatan->realizations->count())
                @foreach ($kegiatan->realizations->sortBy('trx_date') as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->trx_date)->format('d/m/Y') }}</td>
                        <td>{{ $r->type === 'income' ? 'Masuk' : 'Keluar' }}</td>
                        <td>{{ $r->coaAccount?->code ?? '-' }} - {{ $r->coaAccount?->name ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format((int) $r->amount, 0, ',', '.') }}</td>
                        <td>{{ $r->description ?? '-' }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada realisasi.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="sign">
        <div class="box">
            Mengetahui,<br/>Ketua
            <div class="name">____________________</div>
        </div>
        <div class="box">
            Bendahara
            <div class="name">____________________</div>
        </div>
    </div>
</body>
</html>
