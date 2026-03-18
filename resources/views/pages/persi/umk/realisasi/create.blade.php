{{-- resources/views/pages/persi/umk/realisasi/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4" x-data="umkTabs">

            {{-- HEADER --}}
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        UANG MUKA KEGIATAN (UMK)
                    </h1>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-3 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-3 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-bold mb-1">Periksa input:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PANEL WRAPPER (REALISASI = ORANGE) --}}
            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#E77A2E]">

                {{-- TAB HEADER (3 tab) --}}
                <div class="relative">
                    <div class="flex items-end mx-0 px-0">
                        <a href="{{ route('persi.umk.pengajuan') }}"
                            class="tab-umk tab-umk--left tab-umk--green tab-umk--inactive">
                            <span class="tab-umk__text">PENGAJUAN</span>
                        </a>

                        <a href="{{ route('persi.umk.realisasi') }}"
                            class="tab-umk tab-umk--mid tab-umk--orange tab-umk--active">
                            <span class="tab-umk__text">REALISASI</span>
                        </a>

                        <a href="{{ route('persi.umk.rekap') }}"
                            class="tab-umk tab-umk--right tab-umk--yellow tab-umk--inactive">
                            <span class="tab-umk__text tab-umk__text--dark">REKAP</span>
                        </a>
                    </div>

                    {{-- STRIP bawah tab (menyatu ke panel) --}}
                    <div class="h-6 md:h-7 bg-[#E77A2E]"></div>
                </div>

                {{-- CONTENT --}}
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">

                    {{-- HEADER UMK --}}
                    <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="rounded-xl border border-black/10 bg-white p-4">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">No. UMK</div>
                                    <div class="text-sm font-extrabold text-gray-900">
                                        {{ $umk->request_no ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">Tgl. Pengajuan</div>
                                    <div class="text-sm font-bold text-gray-900">
                                        {{ $umk?->request_date ? \Carbon\Carbon::parse($umk->request_date)->format('d/m/Y') : '-' }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">Pos</div>
                                    <div class="text-sm font-bold text-gray-900 text-right">
                                        {{ $umk->cashAccount->name ?? '-' }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">Status</div>
                                    <div>
                                        <span
                                            class="inline-flex rounded-full px-2 py-0.5 text-xs font-bold
                                                {{ ($umk->status ?? 'outstanding') === 'closed' ? 'bg-gray-100 text-gray-700' : 'bg-green-100 text-green-700' }}">
                                            {{ ($umk->status ?? 'outstanding') === 'closed' ? 'Closed' : 'Outstanding' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">Nominal UMK</div>
                                    <div class="text-sm font-extrabold text-gray-900">
                                        Rp {{ number_format((int) ($umk->amount ?? 0), 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="flex items-center justify-between gap-3">
                                    <div class="text-sm font-semibold text-gray-700">Outstanding</div>
                                    <div class="text-lg font-extrabold text-blue-700">
                                        Rp {{ number_format((int) ($umk->outstanding ?? 0), 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-xl border border-black/10 bg-white p-4">
                            <div class="text-sm font-semibold text-gray-700 mb-2">Nama & Deskripsi Kegiatan</div>
                            <div class="font-extrabold text-gray-900">
                                {{ $umk->activity_name ?? '-' }}
                            </div>
                            <div
                                class="mt-2 min-h-[74px] rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800">
                                {{ $umk->activity_description ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Divider dashed --}}
                    <div class="my-4 border-t-2 border-dashed border-[#0F3F3B]/60"></div>

                    {{-- FORM REALISASI --}}
                    <form method="POST" action="{{ route('persi.umk.realisasi.store', $umk->id) }}"
                        class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                        @csrf

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Tanggal</label>
                            <input type="date" name="trx_date" value="{{ old('trx_date', now()->toDateString()) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif required>
                            @error('trx_date')
                                <div class="mt-1 text-xs text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Jenis Transaksi</label>
                            <select name="type"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif required>
                                <option value="expense" @selected(old('type', 'expense') === 'expense')>Pengeluaran</option>
                                <option value="income" @selected(old('type') === 'income')>Penerimaan (Pengembalian)</option>
                            </select>
                            @error('type')
                                <div class="mt-1 text-xs text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ✅ COA --}}
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">COA</label>
                            <select name="coa_account_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif required>
                                <option value="">Pilih COA</option>
                                @foreach ($coaAccounts as $coa)
                                    <option value="{{ $coa->id }}" @selected((string) old('coa_account_id') === (string) $coa->id)>
                                        {{ $coa->code }} - {{ $coa->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coa_account_id')
                                <div class="mt-1 text-xs text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Jml. Realisasi (Rp.)</label>
                            <input type="text" name="amount" value="{{ old('amount') }}"
                                class="rupiah w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                placeholder="Rp 0" inputmode="numeric" @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif
                                required>
                            @error('amount')
                                <div class="mt-1 text-xs text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-12">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Keterangan</label>
                            <textarea name="description" rows="3" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                placeholder="Keterangan..." @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="mt-1 text-xs text-red-700">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="md:col-span-12 flex flex-col md:flex-row gap-2 md:items-center md:justify-between mt-2">
                            <div class="flex flex-col sm:flex-row gap-2">
                                <form method="POST" action="{{ route('persi.umk.realisasi.close', $umk->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-full bg-red-600 px-7 py-3 text-sm font-extrabold text-white shadow hover:brightness-95"
                                        @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif
                                        onclick="return confirm('Yakin ingin closing UMK ini?')">
                                        CLOSING UMK
                                    </button>
                                </form>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('persi.trx.index') }}?q=UMK-{{ $umk->request_no ?? '' }}"
                                    class="inline-flex items-center justify-center rounded-full bg-blue-500 px-7 py-3 text-sm font-extrabold text-white shadow hover:brightness-95">
                                    LIHAT MUTASI
                                </a>

                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#0F3F3B] px-10 py-3 text-sm font-extrabold text-white shadow hover:brightness-110
                                    @if (($umk->status ?? 'outstanding') === 'closed') opacity-50 cursor-not-allowed @endif"
                                    @if (($umk->status ?? 'outstanding') === 'closed') disabled @endif>
                                    SIMPAN
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- LIST REALISASI --}}
                    <div class="mt-6">
                        <div class="text-sm font-extrabold text-[#0F3F3B] mb-2">DETAIL REALISASI</div>

                        <div class="overflow-x-auto rounded-xl border border-black/10 bg-white">
                            <table class="min-w-full text-sm">
                                <thead class="bg-[#E77A2E] text-white">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Jenis</th>
                                        <th class="px-4 py-3">COA</th>
                                        <th class="px-4 py-3 text-right">Nominal</th>
                                        <th class="px-4 py-3">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-black/10">
                                    @if ($umk && $umk->realizations && $umk->realizations->count())
                                        @foreach ($umk->realizations->sortByDesc('trx_date') as $r)
                                            <tr class="hover:bg-black/5">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{ $r->trx_date ? \Carbon\Carbon::parse($r->trx_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex rounded-full px-2 py-1 text-xs font-bold
                                                        {{ $r->type === 'expense' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                        {{ $r->type === 'expense' ? 'Pengeluaran' : 'Pengembalian' }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    {{-- kalau relasi belum ada, aman '-' --}}
                                                    {{ $r->cashTransaction?->coaAccount?->code ?? '-' }}
                                                    - {{ $r->cashTransaction?->coaAccount?->name ?? '-' }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right font-extrabold">
                                                    Rp {{ number_format((int) $r->amount, 0, ',', '.') }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $r->description ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-4 py-6 text-center text-gray-600">
                                                Belum ada realisasi.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="px-4 md:px-6 pb-4 md:pb-6 text-center text-xs italic text-white/80">
                    Hak Cipta Milik Allah Semata
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* ===== UMK TAB (3) ===== */
        .tab-umk {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: flex-start;
            height: 58px;
            padding: 16px 22px;
            flex: 1 1 0%;
            min-width: 0;
            border-bottom: 0;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            transition: transform .15s ease, filter .15s ease;
            text-decoration: none;
        }

        .tab-umk--left {
            border-top-right-radius: 10px;
        }

        .tab-umk--mid {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .tab-umk--right {
            border-top-left-radius: 10px;
        }

        .tab-umk__text {
            font-weight: 900;
            letter-spacing: .4px;
            font-size: 18px;
            line-height: 1;
            color: #F5F1E6;
            text-shadow: 0 1px 0 rgba(0, 0, 0, .25);
        }

        .tab-umk__text--dark {
            color: #0F3F3B;
            text-shadow: none;
        }

        .tab-umk--green {
            background: #0F3F3B;
        }

        .tab-umk--orange {
            background: #E77A2E;
        }

        .tab-umk--yellow {
            background: #F6E88E;
        }

        .tab-umk--active {
            z-index: 20;
            filter: none;
        }

        .tab-umk--inactive {
            z-index: 10;
            filter: brightness(.97) saturate(.95);
        }

        .tab-umk:hover {
            filter: brightness(1.03);
        }

        @media (max-width: 640px) {
            .tab-umk {
                height: 52px;
                padding: 14px 14px;
            }

            .tab-umk__text {
                font-size: 14px;
            }
        }
    </style>

    <script>
        // Rupiah formatter for inputs with class "rupiah"
        (function() {
            const fmt = (val) => {
                const n = String(val || '').replace(/[^\d]/g, '');
                if (!n) return '';
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(n, 10));
            };

            document.addEventListener('input', (e) => {
                const el = e.target;
                if (!el.classList || !el.classList.contains('rupiah')) return;
                const old = el.value;
                el.value = fmt(old);
                try {
                    el.setSelectionRange(el.value.length, el.value.length);
                } catch (err) {}
            });
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('umkTabs', () => ({
                activeTab: 'realisasi',
            }))
        })
    </script>
@endsection
