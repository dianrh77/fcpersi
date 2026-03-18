@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4" x-data="umkRealisasiList">

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

            {{-- PANEL (REALISASI = ORANGE) --}}
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
                    <div class="h-6 md:h-7 bg-[#E77A2E]"></div>
                </div>

                {{-- CONTENT --}}
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6 dt-orange">

                    <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm font-extrabold tracking-wide text-[#0F3F3B]">
                            PILIH UMK UNTUK REALISASI
                        </div>
                        <div class="text-xs text-gray-700">
                            Ketik nomor / kegiatan / pos untuk mencari.
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-2 px-2">
                        <table id="tableUmk" class="whitespace-nowrap w-full"></table>
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
    <script src="{{ asset('js/simple-datatables.js') }}"></script>

    <style>
        [x-cloak] {
            display: none !important
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
            border-top-right-radius: 10px
        }

        .tab-umk--mid {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px
        }

        .tab-umk--right {
            border-top-left-radius: 10px
        }

        .tab-umk__text {
            font-weight: 900;
            letter-spacing: .4px;
            font-size: 18px;
            line-height: 1;
            color: #F5F1E6;
            text-shadow: 0 1px 0 rgba(0, 0, 0, .25)
        }

        .tab-umk__text--dark {
            color: #0F3F3B;
            text-shadow: none
        }

        .tab-umk--green {
            background: #0F3F3B
        }

        .tab-umk--orange {
            background: #E77A2E
        }

        .tab-umk--yellow {
            background: #F6E88E
        }

        .tab-umk--active {
            z-index: 20;
            filter: none
        }

        .tab-umk--inactive {
            z-index: 10;
            filter: brightness(.97) saturate(.95)
        }

        .tab-umk:hover {
            filter: brightness(1.03)
        }

        @media (max-width: 640px) {
            .tab-umk {
                height: 52px;
                padding: 14px 14px
            }

            .tab-umk__text {
                font-size: 14px
            }
        }

        /* ===== Simple-DataTables: search kanan + mobile scroll ===== */
        .dataTable-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px
        }

        .dataTable-dropdown {
            order: 1
        }

        .dataTable-search {
            order: 2;
            margin-left: auto;
            display: flex;
            justify-content: flex-end;
            width: min(420px, 100%)
        }

        .dataTable-search .dataTable-input {
            width: 100%;
            border-radius: 9999px;
            padding: 10px 14px;
            border: 1px solid rgba(15, 63, 59, .15);
            background: #fff;
            outline: none
        }

        .dataTable-selector {
            border-radius: 9999px;
            padding: 8px 12px;
            border: 1px solid rgba(15, 63, 59, .15);
            background: #fff;
            outline: none
        }

        .dataTable-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 14px
        }

        .dataTable-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: .875rem
        }

        .dataTable-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            color: #fff;
            font-weight: 800;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            white-space: nowrap
        }

        .dataTable-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(0, 0, 0, .08);
            vertical-align: top;
            white-space: nowrap
        }

        .dataTable-table tbody tr:nth-child(even) td {
            background: rgba(0, 0, 0, .02)
        }

        .dataTable-table tbody tr:hover td {
            background: rgba(0, 0, 0, .04)
        }

        .dataTable-pagination a {
            border-radius: 9999px
        }

        @media (max-width: 640px) {
            .dataTable-search {
                width: 100%
            }

            .dataTable-table {
                font-size: .8125rem
            }

            .dataTable-table thead th,
            .dataTable-table tbody td {
                padding: 10px 12px
            }
        }

        .dt-orange .dataTable-table thead th {
            background: #E77A2E
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('umkRealisasiList', () => ({
                all: @json($umks ?? []),
                table: null,

                rupiah(n) {
                    const num = parseInt(n || 0, 10) || 0;
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
                },

                fmtDate(iso) {
                    if (!iso) return '-';
                    const [y, m, d] = String(iso).split('-');
                    if (!y || !m || !d) return iso;
                    return `${d}/${m}/${y}`;
                },

                init() {
                    if (typeof simpleDatatables === 'undefined') {
                        console.error('simpleDatatables belum ter-load.');
                        return;
                    }

                    const headings = ['Aksi', 'No UMK', 'Tanggal', 'Kegiatan', 'Pos', 'Nominal',
                        'Status'
                    ];
                    const rows = (this.all || []).map(u => ([
                        String(u.id ?? '-'),
                        String(u.request_no ?? '-'),
                        String(u.request_date ?? '-'),
                        String(u.activity_name ?? '-'),
                        String(u.pos ?? '-'),
                        String(u.amount ?? 0),
                        String(u.status ?? '-'),
                    ]));

                    this.table = new simpleDatatables.DataTable('#tableUmk', {
                        searchable: true,
                        perPage: 25,
                        perPageSelect: [10, 25, 50, 100],
                        fixedHeight: false,
                        labels: {
                            placeholder: "Cari: nomor / kegiatan / pos ...",
                            noRows: "Belum ada UMK.",
                            info: "Menampilkan {start} - {end} dari {rows} data",
                            noResults: "Data tidak ditemukan",
                        },
                        data: {
                            headings,
                            data: rows
                        },
                        columns: [{
                                select: 2,
                                render: (data) => this.fmtDate(data)
                            },
                            {
                                select: 5,
                                render: (data) =>
                                    `<span class="font-extrabold text-gray-900">${this.rupiah(data)}</span>`
                            },
                            {
                                select: 0,
                                sortable: false,
                                render: (cell) => {
                                    const id = String(cell ?? '');
                                    const base = @json(url('/persi/umk/realisasi'));
                                    const url = `${base}/${id}/create`;
                                    return `
                                        <div class="flex items-center justify-center">
                                            <a href="${url}"
                                               class="inline-flex items-center rounded-full bg-[#0F3F3B] px-4 py-2 text-xs font-extrabold text-white hover:brightness-110">
                                                PILIH
                                            </a>
                                        </div>
                                    `;
                                }
                            }
                        ]
                    });

                    this.table.columns().sort(2, 'desc'); // tanggal desc
                }
            }))
        })
    </script>
@endsection
