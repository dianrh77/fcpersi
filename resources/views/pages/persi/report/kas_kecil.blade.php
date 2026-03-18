@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        REPORT KAS KECIL
                    </h1>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#0F3F3B]">
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end mb-4">
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Dari</label>
                            <input type="date" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Sampai</label>
                            <input type="date" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Kas</label>
                            <select class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                                <option>Semua</option>
                            </select>
                        </div>
                        <div class="md:col-span-3 flex md:justify-end">
                            <button class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-extrabold text-white shadow hover:brightness-95">
                                TAMPILKAN
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-black/10 bg-white">
                        <table class="min-w-full text-sm">
                            <thead class="bg-green-700 text-white">
                                <tr>
                                    <th class="px-4 py-3">Tanggal</th>
                                    <th class="px-4 py-3">Uraian</th>
                                    <th class="px-4 py-3 text-right">Masuk</th>
                                    <th class="px-4 py-3 text-right">Keluar</th>
                                    <th class="px-4 py-3 text-right">Saldo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/10">
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-600">
                                        Belum ada data report.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="px-4 md:px-6 pb-4 md:pb-6 text-center text-xs italic text-white/80">
                    Hak Cipta Milik Allah Semata
                </div>
            </div>
        </div>
    </div>
@endsection
