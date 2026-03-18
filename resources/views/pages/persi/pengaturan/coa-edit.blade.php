@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#0F3F3B] dark:text-white">EDIT COA</h1>
                <a href="{{ route('persi.pengaturan') }}"
                    class="text-sm font-semibold text-[#0F3F3B] hover:underline">Kembali</a>
            </div>

            @if ($errors->any())
                <div class="mt-3 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
                    <div class="mb-1 font-bold">Periksa input:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 rounded-2xl bg-[#0F3F3B] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="rounded-xl bg-[#F5F1E6] p-4 md:p-6">
                    <form method="POST" action="{{ route('persi.pengaturan.coa.update', $coa->id) }}"
                        class="grid grid-cols-1 gap-4 md:grid-cols-5">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Kode</label>
                            <input type="text" name="code" value="{{ old('code', $coa->code) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $coa->name) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Tipe</label>
                            <select name="type" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm"
                                required>
                                <option value="income" @selected(old('type', $coa->type) === 'income')>Penerimaan</option>
                                <option value="expense" @selected(old('type', $coa->type) === 'expense')>Pengeluaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Parent (Opsional)</label>
                            <select name="parent_id"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                                <option value="">-</option>
                                @foreach ($coaParents as $p)
                                    <option value="{{ $p->id }}" @selected((string) old('parent_id', $coa->parent_id) === (string) $p->id)>
                                        {{ str_repeat('—', max(0, (int) $p->level - 1)) }} {{ $p->code }} -
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coa->is_active))
                                    class="rounded border-gray-300">
                                Aktif
                            </label>
                        </div>
                        <div class="md:col-span-5 flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                                SIMPAN PERUBAHAN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
