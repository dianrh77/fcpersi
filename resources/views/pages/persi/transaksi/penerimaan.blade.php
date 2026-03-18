{{-- resources/views/pages/persi/transaksi/penerimaan.blade.php --}}
@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#0F3F3B] dark:text-white">INPUT PENERIMAAN</h1>
                <a href="{{ route('persi.trx.index', ['type' => 'income']) }}"
                    class="text-sm font-semibold text-[#0F3F3B] hover:underline">
                    ← Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="mt-3 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="font-bold mb-1">Periksa input:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-4 rounded-2xl bg-[#0F3F3B] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="rounded-xl bg-[#F5F1E6] p-4 md:p-6">

                    <form method="POST" action="{{ route('persi.trx.penerimaan.store') }}"
                        class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf

                        {{-- Tanggal --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Tanggal</label>
                            <input type="date" name="trx_date" value="{{ old('trx_date', now()->toDateString()) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                        </div>

                        {{-- Pos --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Pos</label>
                            <select name="cash_account_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                                <option value="">Pilih Pos</option>
                                @foreach ($cashAccounts as $acc)
                                    <option value="{{ $acc->id }}" @selected((string) old('cash_account_id') === (string) $acc->id)>
                                        {{ $acc->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- COA (utama) --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Nama Penerimaan (COA)</label>
                            <select name="coa_account_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                                <option value="">Pilih COA Penerimaan</option>

                                @foreach ($coaGroups as $group)
                                    @php $label = $group->code . ' - ' . strtoupper($group->name); @endphp
                                    <optgroup label="{{ $label }}">
                                        @foreach ($group->children as $coa)
                                            <option value="{{ $coa->id }}" @selected((string) old('coa_account_id') === (string) $coa->id)>
                                                {{ $coa->code }} - {{ $coa->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nama RS (opsional) --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Nama RS (Opsional)</label>
                            <select name="member_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                                <option value="">- (Opsional) -</option>
                                @foreach ($members as $m)
                                    <option value="{{ $m->id }}" @selected((string) old('member_id') === (string) $m->id)>
                                        {{ $m->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nominal (format rupiah) --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Nominal (Rp.)</label>

                            {{-- input tampilan (pakai titik) --}}
                            <input type="text" id="amount_display" inputmode="numeric"
                                value="{{ old('amount') ? number_format((int) old('amount'), 0, ',', '.') : '' }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                placeholder="Contoh: 1.500.000" required>

                            {{-- input hidden untuk nilai asli (integer) yang dikirim ke server --}}
                            <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">

                            <div class="mt-1 text-xs text-gray-600">Ketik angka, otomatis jadi format Rp.</div>
                        </div>

                        {{-- No Referensi --}}
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">No. Referensi (opsional)</label>
                            <input type="text" name="reference_no" value="{{ old('reference_no') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                placeholder="Contoh: INV-001">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Deskripsi Transaksi</label>
                            <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                placeholder="Keterangan...">{{ old('description') }}</textarea>
                        </div>

                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-full bg-[#E77A2E] px-7 py-3 text-sm font-bold text-white shadow hover:brightness-95">
                                SIMPAN PENERIMAAN
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function formatRupiahDigits(value) {
            const digits = (value || '').replace(/[^\d]/g, '');
            if (!digits) return '';
            return digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const display = document.getElementById('amount_display');
        const hidden = document.getElementById('amount');

        // init (jaga-jaga)
        hidden.value = (hidden.value || '').toString().replace(/[^\d]/g, '');

        display.addEventListener('input', function() {
            const digits = this.value.replace(/[^\d]/g, '');
            this.value = formatRupiahDigits(digits);
            hidden.value = digits; // yang dikirim ke server: 1500000
        });

        // Saat submit, pastikan hidden sudah terisi angka
        display.closest('form').addEventListener('submit', function() {
            hidden.value = (display.value || '').replace(/[^\d]/g, '');
        });
    </script>
@endsection
