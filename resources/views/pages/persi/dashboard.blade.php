@extends('layouts.app')

@section('content')
    @php
        $saldoKasKecil = $saldoKasKecil ?? 0;
        $saldoKasBesar = $saldoKasBesar ?? 0;
        $incomeThisMonth = $incomeThisMonth ?? 0;
        $expenseThisMonth = $expenseThisMonth ?? 0;

        $incomeByMonthSafe = $incomeByMonth ?? array_fill(0, 12, 0);
        $expenseByMonthSafe = $expenseByMonth ?? array_fill(0, 12, 0);

        $userName = auth()->user()->name ?? 'User';
    @endphp

    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">

            {{-- HEADER AREA (judul kanan “DASHBOARD” biar tidak numpuk) --}}
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <h1
                    class="text-3xl font-bold text-[#f7f492] dark:text-white rounded-t-xl bg-[#E77A2E] dark:bg-[#0F3F3B] px-2">
                    Selamat Datang {{ $userName }}
                </h1>
                <div class="text-3xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                    DASHBOARD
                </div>
            </div>

            {{-- PANEL UTAMA (hijau tua seperti PDF) --}}
            <div class="rounded-b-2xl bg-[#0F3F3B] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">

                {{-- WELCOME STRIP ORANYE --}}
                {{-- <div class="rounded-xl bg-[#E77A2E] px-5 py-3 text-white font-semibold">
                    Selamat Datang User ...
                </div> --}}

                {{-- KARTU RINGKASAN (4 kartu) --}}
                <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    {{-- Card --}}
                    <div class="rounded-xl bg-[#F5F1E6] p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-700">Saldo Kas Kecil</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    Rp {{ number_format($saldoKasKecil, 0, ',', '.') }}
                                </div>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#E6B14A] text-white font-bold">
                                Rp
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <a href="{{ route('persi.report.kas_kecil') }}"
                                class="text-xs font-semibold text-[#7C3AED] hover:underline">See Details</a>
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#F5F1E6] p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-700">Saldo Bank</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    Rp {{ number_format($saldoKasBesar, 0, ',', '.') }}
                                </div>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#E6B14A] text-white font-bold">
                                Rp
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <a href="{{ route('persi.report.bank') }}"
                                class="text-xs font-semibold text-[#7C3AED] hover:underline">See Details</a>
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#F5F1E6] p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-700">Penerimaan</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}
                                </div>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#E6B14A] text-white font-bold">
                                Rp
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <a href="{{ route('persi.report.cashflow') }}"
                                class="text-xs font-semibold text-[#7C3AED] hover:underline">See Details</a>
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#F5F1E6] p-4 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-semibold uppercase text-gray-700">Pengeluaran</div>
                                <div class="mt-1 text-lg font-bold text-gray-900">
                                    Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}
                                </div>
                            </div>
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-full bg-[#E6B14A] text-white font-bold">
                                Rp
                            </div>
                        </div>
                        <div class="mt-3 text-right">
                            <a href="{{ route('persi.report.cashflow') }}"
                                class="text-xs font-semibold text-[#7C3AED] hover:underline">See Details</a>
                        </div>
                    </div>
                </div>

                {{-- CHARTS (2 kolom) --}}
                <div class="mt-4 grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="rounded-xl bg-[#F5F1E6] p-4">
                        <div class="mb-2 text-sm font-bold text-gray-900">Income per Month</div>
                        <div class="h-[260px]">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>

                    <div class="rounded-xl bg-[#F5F1E6] p-4">
                        <div class="mb-2 text-sm font-bold text-gray-900">Expense per Month</div>
                        <div class="h-[260px]">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- BUTTON INPUT TRANSAKSI (kanan bawah) --}}
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('persi.trx.penerimaan') }}"
                        class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-3 text-sm font-bold text-white shadow hover:brightness-95">
                        INPUT TRANSAKSI
                    </a>
                </div>

            </div>



        </div>
    </div>

    {{-- Chart.js (kalau TailAdmin kamu belum include chart lib) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const incomeData = @json($incomeByMonthSafe);
        const expenseData = @json($expenseByMonthSafe);

        const baseOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        callback: (v) => new Intl.NumberFormat('id-ID').format(v)
                    }
                }
            }
        };

        new Chart(document.getElementById('incomeChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data: incomeData,
                    borderRadius: 8,
                }]
            },
            options: baseOptions
        });

        new Chart(document.getElementById('expenseChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    data: expenseData,
                    borderRadius: 8,
                }]
            },
            options: baseOptions
        });
    </script>
@endsection
