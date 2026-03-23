{{-- resources/views/pages/persi/transaksi/index.blade.php --}}
@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] overflow-x-hidden rounded-xl bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] ring-1 ring-[#e7ddc3] dark:from-gray-900 dark:to-gray-900 dark:ring-gray-800">
        <div class="mx-auto w-full max-w-full min-w-0 p-4 md:p-5" x-data="trxTabs">

            {{-- HEADER PAGE --}}
            <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">TRANSAKSI</h1>
                    <div class="text-xs font-semibold text-gray-600">Penerimaan & Pengeluaran</div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PANEL (Income = Hijau, Expense = Orange) --}}
            <div class="panel-trx overflow-hidden rounded-2xl shadow-[0_14px_36px_rgba(0,0,0,0.16)]"
                :class="activeTab === 'income' ? 'bg-[#0F3F3B]' : 'bg-[#E77A2E]'">

                {{-- TAB MODEL PDF --}}
                <div class="relative">
                    <div class="flex items-end mx-0 px-0">
                        {{-- TAB: PENERIMAAN --}}
                        <button type="button" @click="switchTab('income')" class="tab-umk tab-umk--left tab-umk--green"
                            :class="activeTab === 'income' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">PENERIMAAN</span>
                        </button>

                        {{-- TAB: PENGELUARAN --}}
                        <button type="button" @click="switchTab('expense')" class="tab-umk tab-umk--right tab-umk--orange"
                            :class="activeTab === 'expense' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">PENGELUARAN</span>
                        </button>
                    </div>
                    <div class="h-6 md:h-7" :class="activeTab === 'income' ? 'bg-[#0F3F3B]' : 'bg-[#E77A2E]'"></div>
                </div>

                {{-- CONTENT TABLE --}}
                <div class="trx-surface mx-2 my-2 min-w-0 rounded-xl bg-[#F5F1E6] p-4 md:p-6"
                    :class="activeTab === 'income' ? 'dt-green' : 'dt-orange'">

                    {{-- Toolbar (button mengikuti tab aktif) --}}
                    <div class="trx-toolbar mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm font-extrabold tracking-wide text-[#0F3F3B]">
                            <span x-text="activeTab === 'income' ? 'DATA PENERIMAAN' : 'DATA PENGELUARAN'"></span>
                        </div>

                        <a :href="activeTab === 'income' ? incomeUrl : expenseUrl"
                            class="trx-action-btn inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-bold text-white shadow hover:brightness-105"
                            :class="activeTab === 'income' ? 'bg-[#0F3F3B]' : 'bg-[#E77A2E]'">
                            <span x-text="activeTab === 'income' ? '+ Input Penerimaan' : '+ Input Pengeluaran'"></span>
                        </a>
                    </div>

                    <div x-show="activeTab === 'income'" x-cloak class="min-w-0">
                        <div class="trx-table-shell w-full max-w-full overflow-x-auto">
                            <table id="tableIncome" class="whitespace-nowrap w-full"></table>
                        </div>
                    </div>

                    <div x-show="activeTab === 'expense'" x-cloak class="min-w-0">
                        <div class="trx-table-shell w-full max-w-full overflow-x-auto">
                            <table id="tableExpense" class="whitespace-nowrap w-full"></table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Local / CDN Simple-DataTables --}}
    <script src="{{ asset('js/simple-datatables.js') }}"></script>
    {{-- kalau 404, pakai ini:
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    --}}

    <style>
        [x-cloak] {
            display: none !important;
        }

        .panel-trx {
            position: relative;
            isolation: isolate;
        }

        .panel-trx::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.10), rgba(255, 255, 255, 0));
            pointer-events: none;
            z-index: 0;
        }

        .panel-trx>* {
            position: relative;
            z-index: 1;
        }

        .trx-surface {
            border: 1px solid rgba(255, 255, 255, 0.48);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
        }

        .trx-toolbar {
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(15, 63, 59, 0.18);
        }

        .trx-action-btn {
            transform: translateY(0);
            transition: transform .18s ease, box-shadow .18s ease, filter .18s ease;
        }

        .trx-action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.16);
        }

        .trx-table-shell {
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.35);
        }

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

        .tab-umk--green {
            background: #0F3F3B;
        }

        .tab-umk--orange {
            background: #E77A2E;
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

        /* =========================
                                       Simple-DataTables styling
                                       ========================= */
        .dataTable-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        /* kiri: perPage */
        .dataTable-dropdown {
            order: 1;
        }

        /* kanan: search */
        .dataTable-search {
            order: 2;
            margin-left: auto;
            display: flex;
            justify-content: flex-end;
            width: min(420px, 100%);
        }

        .dataTable-search .dataTable-input {
            width: 100%;
            border-radius: 9999px;
            padding: 10px 14px;
            border: 1px solid rgba(15, 63, 59, 0.15);
            background: #fff;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .dataTable-search .dataTable-input:focus {
            border-color: rgba(15, 63, 59, 0.45);
            box-shadow: 0 0 0 3px rgba(15, 63, 59, 0.12);
        }

        .dataTable-selector {
            border-radius: 9999px;
            padding: 8px 12px;
            border: 1px solid rgba(15, 63, 59, 0.15);
            background: #fff;
            outline: none;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .dataTable-selector:focus {
            border-color: rgba(15, 63, 59, 0.45);
            box-shadow: 0 0 0 3px rgba(15, 63, 59, 0.12);
        }

        .dataTable-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 14px;
            max-width: 100%;
            scrollbar-width: thin;
            scrollbar-color: rgba(15, 63, 59, 0.6) rgba(255, 255, 255, 0.35);
        }

        .dataTable-container::-webkit-scrollbar {
            height: 10px;
            width: 10px;
        }

        .dataTable-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.35);
            border-radius: 9999px;
        }

        .dataTable-container::-webkit-scrollbar-thumb {
            background: rgba(15, 63, 59, 0.6);
            border-radius: 9999px;
        }

        .dataTable-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        .dataTable-table {
            width: 100%;
            min-width: max-content;
            /* table-layout: fixed; */
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.875rem;
        }

        /* sticky header */
        .dataTable-table thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            color: #fff;
            font-weight: 800;
            padding: 12px 14px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            white-space: nowrap;
            overflow: hidden;
            box-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);
            /* text-overflow: ellipsis; */
        }

        .dataTable-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            vertical-align: top;
            white-space: nowrap;
            /* overflow: hidden; */
            /* text-overflow: ellipsis; */
        }

        .dataTable-table tbody tr:nth-child(even) td {
            background: rgba(0, 0, 0, 0.028);
        }

        .dataTable-table tbody tr:hover td {
            background: rgba(15, 63, 59, 0.08);
        }

        .dataTable-pagination a {
            border-radius: 9999px;
            transition: all .18s ease;
        }

        .dataTable-pagination a:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 640px) {
            .dataTable-top {
                gap: 10px;
            }

            .dataTable-search {
                width: 100%;
            }

            .dataTable-table {
                font-size: 0.8125rem;
            }

            .dataTable-table thead th,
            .dataTable-table tbody td {
                padding: 10px 12px;
            }
        }

        /* header color follow tab */
        .dt-green .dataTable-table thead th {
            background: #0F3F3B;
        }

        .dt-orange .dataTable-table thead th {
            background: #E77A2E;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.documentElement.style.overflowX = 'hidden';
            document.body.style.overflowX = 'hidden';
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('trxTabs', () => ({
                all: @json($transactions),

                income: [],
                expense: [],

                tableIncome: null,
                tableExpense: null,

                activeTab: 'income',

                // url input (mengikuti tab aktif)
                incomeUrl: @json(route('persi.trx.penerimaan')),
                expenseUrl: @json(route('persi.trx.pengeluaran')),

                init() {
                    this.splitData();
                    this.initTables();
                },

                switchTab(tab) {
                    this.activeTab = tab;

                    setTimeout(() => {
                        if (tab === 'income' && this.tableIncome) this.tableIncome.refresh();
                        if (tab === 'expense' && this.tableExpense) this.tableExpense.refresh();
                    }, 80);
                },

                splitData() {
                    this.income = (this.all || []).filter(t => t.type === 'income');
                    this.expense = (this.all || []).filter(t => t.type === 'expense');
                },

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

                sanitizeRows(rows, colCount) {
                    return (rows || []).map(r => {
                        const safe = [];
                        for (let i = 0; i < colCount; i++) {
                            safe.push(String((r && r[i] !== undefined && r[i] !== null) ? r[i] :
                                '-'));
                        }
                        return safe;
                    });
                },

                rows(items) {
                    return (items || []).map(t => ([
                        String(t.id ?? '-'),
                        String(t.trx_date ?? '-'),
                        String(t.type ?? '-'),
                        String(t.pos ?? '-'),
                        String(t.coa ?? '-'),
                        String(t.member ?? '-'),
                        String(t.amount ?? 0),
                        String(t.reference_no ?? '-'),
                        String(t.description ?? '-'),
                    ]));
                },

                initTables() {
                    if (typeof simpleDatatables === 'undefined') {
                        console.error(
                            'simpleDatatables belum ter-load. Pastikan file ada di public/js/simple-datatables.js'
                        );
                        return;
                    }

                    const headings = ['Aksi', 'Tanggal', 'Tipe', 'Pos', 'COA', 'RS/Member', 'Nominal',
                        'No Ref',
                        'Keterangan'
                    ];

                    const incomeRows = this.sanitizeRows(this.rows(this.income), headings.length);
                    const expenseRows = this.sanitizeRows(this.rows(this.expense), headings.length);

                    this.tableIncome = this.createTable('#tableIncome', headings, incomeRows);
                    this.tableExpense = this.createTable('#tableExpense', headings, expenseRows);

                    this.tableIncome.columns().sort(1, 'desc');
                    this.tableExpense.columns().sort(1, 'desc');
                },

                createTable(selector, headings, dataRows) {
                    return new simpleDatatables.DataTable(selector, {
                        searchable: true,
                        perPage: 25,
                        perPageSelect: [10, 25, 50, 100],
                        fixedHeight: false,
                        labels: {
                            placeholder: "Cari (COA / RS / Ket / Ref)...",
                            noRows: "Belum ada data.",
                            info: "Menampilkan {start} - {end} dari {rows} data",
                            noResults: "Data tidak ditemukan",
                        },
                        data: {
                            headings,
                            data: dataRows
                        },
                        columns: [{
                                select: 1,
                                render: (data) => this.fmtDate(data)
                            },
                            {
                                select: 2,
                                render: (data) => {
                                    const val = String(data ?? '').toLowerCase();
                                    const isIncome = val === 'income';
                                    const color = isIncome ? 'success' : 'danger';
                                    const text = isIncome ? 'Penerimaan' :
                                        'Pengeluaran';
                                    return `<span class="badge badge-outline-${color}">${text}</span>`;
                                }
                            },
                            {
                                select: 6,
                                render: (data) =>
                                    `<span class="font-bold text-gray-900">${this.rupiah(data)}</span>`
                            },
                            {
                                select: 0,
                                sortable: false,
                                render: (cell) => {
                                    const id = String(cell ?? '-');
                                    return `
                                        <div class="flex items-center gap-1 justify-center">
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="navigator.clipboard.writeText('${id}')">
                                                📋 ID
                                            </button>
                                        </div>
                                    `;
                                }
                            },
                        ]
                    });
                }
            }));
        });
    </script>
@endsection
