@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4" x-data="kegiatanTabs">

            {{-- HEADER --}}
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        KEGIATAN
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

            {{-- PANEL (PENGAJUAN = HIJAU) --}}
            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#0F3F3B]">

                {{-- TAB HEADER (3 tab) --}}
                <div class="relative">
                    <div class="flex items-end mx-0 px-0">
                        <a href="{{ route('persi.kegiatan.pengajuan') }}"
                            class="tab-umk tab-umk--left tab-umk--green tab-umk--active">
                            <span class="tab-umk__text">PENGAJUAN</span>
                        </a>

                        <a href="{{ route('persi.kegiatan.realisasi') }}"
                            class="tab-umk tab-umk--mid tab-umk--orange tab-umk--inactive">
                            <span class="tab-umk__text">REALISASI</span>
                        </a>

                        <a href="{{ route('persi.kegiatan.rekap') }}"
                            class="tab-umk tab-umk--right tab-umk--yellow tab-umk--inactive">
                            <span class="tab-umk__text tab-umk__text--dark">REKAP</span>
                        </a>
                    </div>
                    <div class="h-6 md:h-7 bg-[#0F3F3B]"></div>
                </div>

                {{-- CONTENT --}}
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">

                    <form method="POST" action="{{ route('persi.kegiatan.pengajuan.store') }}"
                        class="grid grid-cols-1 md:grid-cols-12 gap-4 items-start">
                        @csrf

                        {{-- KIRI --}}
                        <div class="md:col-span-6">
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-700">Tgl. Kegiatan</label>
                                    <input type="date" name="activity_date"
                                        value="{{ old('activity_date', now()->toDateString()) }}"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                        required>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-700">Sumber Dana (Pos)</label>
                                    <select name="cash_account_id"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                        required>
                                        <option value="">Pilih Pos</option>
                                        @foreach ($cashAccounts as $acc)
                                            <option value="{{ $acc->id }}" @selected((string) old('cash_account_id') === (string) $acc->id)>
                                                {{ $acc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-700">Nama Kegiatan</label>
                                    <input type="text" name="activity_name" value="{{ old('activity_name') }}"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                        placeholder="Contoh: Seminar XXXXXXXX" required>
                                </div>

                                <div>
                                    <label class="mb-1 block text-xs font-semibold text-gray-700">Anggaran (Rp.)</label>
                                    <input type="text" name="budget_amount" value="{{ old('budget_amount') }}"
                                        class="rupiah w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                        placeholder="Rp 0" inputmode="numeric" required>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN --}}
                        <div class="md:col-span-6">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Deskripsi Kegiatan</label>
                            <textarea name="activity_description" rows="6"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" placeholder="Deskripsi kegiatan...">{{ old('activity_description') }}</textarea>

                            <div class="mt-4 flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-[#E77A2E] px-10 py-3 text-sm font-extrabold text-white shadow hover:brightness-95">
                                    SIMPAN PENGAJUAN
                                </button>
                            </div>
                        </div>
                    </form>

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
        (function() {
            const fmt = (val) => {
                const n = String(val || '').replace(/[^\d]/g, '');
                if (!n) return '';
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(parseInt(n, 10));
            };

            document.addEventListener('input', (e) => {
                const el = e.target;
                if (!el.classList || !el.classList.contains('rupiah')) return;
                el.value = fmt(el.value);
                el.setSelectionRange(el.value.length, el.value.length);
            });
        })();

        document.addEventListener('alpine:init', () => {
            Alpine.data('kegiatanTabs', () => ({
                activeTab: 'pengajuan',
            }))
        })
    </script>
@endsection
