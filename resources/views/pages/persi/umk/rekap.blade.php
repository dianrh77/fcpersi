@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4" x-data="umkTabs">

            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        UANG MUKA KEGIATAN (UMK)
                    </h1>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#F6E88E]">

                {{-- TAB HEADER --}}
                <div class="relative">
                    <div class="flex items-end mx-0 px-0">
                        <a href="{{ route('persi.umk.pengajuan') }}" class="tab-umk tab-umk--left tab-umk--green"
                            :class="activeTab === 'pengajuan' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">PENGAJUAN</span>
                        </a>

                        <a href="{{ route('persi.umk.realisasi') }}" class="tab-umk tab-umk--mid tab-umk--orange"
                            :class="activeTab === 'realisasi' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">REALISASI</span>
                        </a>

                        <a href="{{ route('persi.umk.rekap') }}" class="tab-umk tab-umk--right tab-umk--yellow"
                            :class="activeTab === 'rekap' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text tab-umk__text--dark">REKAP</span>
                        </a>
                    </div>

                    <div class="h-6 md:h-7 bg-[#F6E88E]"></div>
                </div>

                {{-- CONTENT --}}
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">

                    <form method="GET" action="{{ route('persi.umk.rekap') }}"
                        class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                        <div class="md:col-span-5">
                            <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">Status Pengajuan</label>
                            <select name="status"
                                class="w-full rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 px-3 py-2 text-sm">
                                <option value="outstanding" @selected(($status ?? 'outstanding') === 'outstanding')>Outstanding</option>
                                <option value="closed" @selected(($status ?? '') === 'closed')>Closed</option>
                            </select>
                        </div>

                        <div class="md:col-span-7">
                            <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">Cari</label>
                            <div class="flex gap-2">
                                <div class="relative w-full">
                                    <input type="text" name="q" value="{{ $q ?? '' }}"
                                        class="w-full rounded-full border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 pl-10 pr-4 py-2 text-sm"
                                        placeholder="No UMK / Nama Kegiatan...">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">🔍</span>
                                </div>

                                <button
                                    class="shrink-0 inline-flex items-center justify-center rounded-full bg-red-600 px-6 py-2 text-sm font-extrabold text-white shadow hover:brightness-95">
                                    CARI
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="my-4 border-t-2 border-dashed border-[#0F3F3B]/60"></div>

                    {{-- SUMMARY --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                        <div
                            class="rounded-xl border border-black/10 bg-gray-100 dark:bg-gray-700/40 px-4 py-3 flex items-center justify-between">
                            <div class="text-sm font-bold text-gray-800 dark:text-gray-200">jml. Pengajuan</div>
                            <div class="text-lg font-extrabold text-[#0F3F3B]">{{ $count ?? 0 }}</div>
                        </div>
                        <div
                            class="rounded-xl border border-black/10 bg-gray-100 dark:bg-gray-700/40 px-4 py-3 flex items-center justify-between">
                            <div class="text-sm font-bold text-gray-800 dark:text-gray-200">jml. Outstanding</div>
                            <div class="text-lg font-extrabold text-blue-700">
                                Rp {{ number_format((int) ($sumOutstanding ?? 0), 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-x-auto rounded-xl border border-black/10 bg-white dark:border-gray-700 dark:bg-gray-800">
                        <table class="min-w-full text-sm text-gray-900 dark:text-gray-100">
                            <thead class="bg-green-600 text-white dark:bg-green-700">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Tgl. Pengajuan</th>
                                    <th class="px-4 py-3">No. UMK</th>
                                    <th class="px-4 py-3">Nama Kegiatan</th>
                                    <th class="px-4 py-3 text-right">Pengajuan</th>
                                    <th class="px-4 py-3 text-right">Penerimaan</th>
                                    <th class="px-4 py-3 text-right">Pengeluaran</th>
                                    <th class="px-4 py-3 text-right">Outstanding</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/10 dark:divide-gray-700 text-gray-900 dark:text-gray-100">
                                @forelse($umks as $i => $u)
                                    @php
                                        $pengajuan = (int) $u->amount;
                                        $pengeluaran = (int) $u->expense_total;
                                        $penerimaan = (int) $u->income_total;
                                        $outstanding = (int) $u->outstanding;
                                    @endphp
                                    <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $i + 1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($u->request_date)->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap font-bold">{{ $u->request_no }}</td>
                                        <td class="px-4 py-3">{{ $u->activity_name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right font-bold">
                                            Rp {{ number_format($pengajuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            Rp {{ number_format($penerimaan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right">
                                            Rp {{ number_format($pengeluaran, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right font-extrabold text-blue-700">
                                            Rp {{ number_format($outstanding, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-600 dark:text-gray-300">
                                            Belum ada data rekap.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        /* reuse tab css */
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('umkTabs', () => ({
                activeTab: 'rekap',
            }))
        })
    </script>
@endsection
