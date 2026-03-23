@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        REPORT CASHFLOW
                    </h1>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#0F3F3B]">
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end mb-4">
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Dari</label>
                            <input type="date" name="start_date" value="{{ $start }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Sampai</label>
                            <input type="date" name="end_date" value="{{ $end }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Pos</label>
                            <select name="cash_account_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                                <option value="">Semua</option>
                                @foreach ($cashAccounts as $account)
                                    <option value="{{ $account->id }}"
                                        @selected((string) $cashAccountId === (string) $account->id)>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3 flex md:justify-end">
                            <button
                                class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-extrabold text-white shadow hover:brightness-95">
                                TAMPILKAN
                            </button>
                        </div>
                    </form>

                    <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                        <div class="rounded-xl border border-black/10 bg-white px-4 py-3">
                            <div class="text-xs font-semibold text-gray-600">Saldo Awal</div>
                            <div class="text-lg font-extrabold text-[#0F3F3B]">
                                Rp {{ number_format($opening, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-black/10 bg-white px-4 py-3">
                            <div class="text-xs font-semibold text-gray-600">Total Masuk</div>
                            <div class="text-lg font-extrabold text-green-700">
                                Rp {{ number_format($totalIn, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="rounded-xl border border-black/10 bg-white px-4 py-3">
                            <div class="text-xs font-semibold text-gray-600">Total Keluar</div>
                            <div class="text-lg font-extrabold text-orange-700">
                                Rp {{ number_format($totalOut, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-black/10 bg-white">
                        <table class="min-w-full text-sm">
                            <thead class="bg-green-700 text-white">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Uraian</th>
                                    <th class="px-4 py-3">Pos</th>
                                    <th class="px-4 py-3">No Ref</th>
                                    <th class="px-4 py-3 text-right">Masuk</th>
                                    <th class="px-4 py-3 text-right">Keluar</th>
                                    <th class="px-4 py-3 text-right">Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/10">
                                <tr class="bg-[#F5F1E6]">
                                    <td class="px-4 py-2 text-xs font-semibold text-gray-700">
                                        {{ $start }}
                                    </td>
                                    <td class="px-4 py-2 text-xs font-semibold text-gray-700">Saldo Awal</td>
                                    <td class="px-4 py-2 text-xs font-semibold text-gray-700">-</td>
                                    <td class="px-4 py-2 text-xs font-semibold text-gray-700">-</td>
                                    <td class="px-4 py-2 text-right text-xs font-semibold text-gray-700">-</td>
                                    <td class="px-4 py-2 text-right text-xs font-semibold text-gray-700">-</td>
                                    <td class="px-4 py-2 text-right text-xs font-semibold text-gray-700">
                                        Rp {{ number_format($opening, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @forelse ($entries as $row)
                                    <tr>
                                        <td class="px-4 py-2">{{ $row['trx_date'] }}</td>
                                        <td class="px-4 py-2">{{ $row['uraian'] }}</td>
                                        <td class="px-4 py-2">{{ $row['pos'] }}</td>
                                        <td class="px-4 py-2">{{ $row['reference_no'] }}</td>
                                        <td class="px-4 py-2 text-right">
                                            {{ $row['in'] ? 'Rp ' . number_format($row['in'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            {{ $row['out'] ? 'Rp ' . number_format($row['out'], 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            Rp {{ number_format($row['balance'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-600">
                                            Belum ada data report.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-[#F5F1E6]">
                                <tr>
                                    <th class="px-4 py-3 text-right" colspan="4">Total</th>
                                    <th class="px-4 py-3 text-right">Rp {{ number_format($totalIn, 0, ',', '.') }}</th>
                                    <th class="px-4 py-3 text-right">Rp {{ number_format($totalOut, 0, ',', '.') }}</th>
                                    <th class="px-4 py-3 text-right">Rp {{ number_format($ending, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
