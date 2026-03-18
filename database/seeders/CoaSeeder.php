<?php

namespace Database\Seeders;

use App\Models\CoaAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Kalau mau aman saat re-run:
            // Hapus dulu COA (pastikan tidak ada FK transaksi dulu ya)
            // CoaAccount::query()->delete();

            $items = [
                // =========================
                // 1 - PENERIMAAN (income)
                // =========================
                ['code' => '1', 'name' => 'PENERIMAAN'],

                ['code' => '1.1', 'name' => 'PENERIMAAN RUTIN'],
                ['code' => '1.1.1', 'name' => 'Pendaftaran anggota baru'],
                ['code' => '1.1.2', 'name' => 'Iuran anggota'],
                ['code' => '1.1.3', 'name' => 'Iuran tunggakan anggota'],

                ['code' => '1.2', 'name' => 'PENERIMAAN PROGRAM'],
                ['code' => '1.2.1', 'name' => 'SHU Seminar dan Expo PERSI Daerah Jawa Tengah'],
                ['code' => '1.2.2', 'name' => 'SHU Workhsop'],
                ['code' => '1.2.3', 'name' => 'SHU Seminar/Webinar'],

                ['code' => '1.3', 'name' => 'PENERIMAAN LAIN'],
                ['code' => '1.3.1', 'name' => 'Penerimaan bunga bank'],
                ['code' => '1.3.2', 'name' => 'Penerimaan bunga deposito'],
                ['code' => '1.3.3', 'name' => 'Cashback Seminar Nasional PERSI Pusat'],
                ['code' => '1.3.4', 'name' => 'Penerimaan Lain'],

                // =========================
                // 2 - PENGELUARAN (expense)
                // =========================
                ['code' => '2', 'name' => 'PENGELUARAN'],

                ['code' => '2.1', 'name' => 'PENGELUARAN RUTIN'],
                ['code' => '2.1.1', 'name' => 'Biaya operasional kantor (ATK, administrasi, kebersihan)'],
                ['code' => '2.1.2', 'name' => 'Biaya gaji pegawai'],
                ['code' => '2.1.3', 'name' => 'Biaya listrik, air, Ipal, zoom dan internet'],
                ['code' => '2.1.4', 'name' => 'Biaya sewa kantor dan perbaikan interior'],
                ['code' => '2.1.5', 'name' => 'Iuran PERSI Pusat'],

                ['code' => '2.2', 'name' => 'PENGELUARAN PROGRAM PENDAMPINGAN RS'],
                ['code' => '2.2.1', 'name' => 'Penugasan menghadiri undangan stake holder PERSI'],
                ['code' => '2.2.2', 'name' => 'Penugasan kredensialing dan pendampingan BPJS'],
                ['code' => '2.2.3', 'name' => 'Penugasan pendampingan RS dengan Dinas Kesehatan (kenaikan kelas dll)'],
                ['code' => '2.2.4', 'name' => 'Penugasan pendampingan RS (kasus RS)'],

                ['code' => '2.3', 'name' => 'PENGELUARAN RAPAT'],
                ['code' => '2.3.1', 'name' => 'Rapat PERSI Daerah dan Komisariat'],
                ['code' => '2.3.2', 'name' => 'Rapat PERSI Pusat dan HOSPEX PERSI Pusat'],
                ['code' => '2.3.3', 'name' => 'Pengeluaran Pelantikan Pengurus PERSI & MAKERSI'],
                ['code' => '2.3.4', 'name' => 'Uang Muka Kegiatan Musywil 2025'],

                ['code' => '2.4', 'name' => 'PENGELUARAN PROGRAM DIKLAT'],
                ['code' => '2.4.1', 'name' => 'Seminar, Workshop, Webinar Gratis'],

                ['code' => '2.5', 'name' => 'PENGELUARAN LAIN'],
                ['code' => '2.5.1', 'name' => 'Biaya Sosial'],
                ['code' => '2.5.2', 'name' => 'Pajak rekening dan deposito'],
            ];

            // Cache code -> id untuk parent lookup
            $codeToId = [];

            foreach ($items as $item) {
                $code = $item['code'];
                $name = trim($item['name']);

                $segments = explode('.', $code);
                $level = count($segments);

                // type dari segmen pertama
                $type = ((int)$segments[0] === 1) ? 'income' : 'expense';

                $parentId = null;
                if ($level > 1) {
                    $parentCode = implode('.', array_slice($segments, 0, -1));
                    $parentId = $codeToId[$parentCode] ?? null;

                    // Kalau parent belum ada (harusnya tidak terjadi)
                    if (!$parentId) {
                        throw new \RuntimeException("Parent COA tidak ditemukan untuk {$code} (parent: {$parentCode})");
                    }
                }

                $coa = CoaAccount::updateOrCreate(
                    ['code' => $code],
                    [
                        'name' => $name,
                        'type' => $type,
                        'level' => $level,
                        'parent_id' => $parentId,
                        'is_active' => true,
                    ]
                );

                $codeToId[$code] = $coa->id;
            }
        });
    }
}
