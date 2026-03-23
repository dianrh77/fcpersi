
@extends('layouts.app')

@section('content')
    @php
        $tab = request('tab', 'coa');
    @endphp

    <div x-data="{
        tab: '{{ $tab }}',
        showCoaCreate: false,
        showCoaEdit: false,
        showClassCreate: false,
        showClassEdit: false,
        showMemberCreate: false,
        showMemberEdit: false,
        toggleBodyLock(isOpen) {
            document.body.style.overflow = isOpen ? 'hidden' : 'unset';
        }
    }"
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-[#0F3F3B] dark:text-white">PENGATURAN</h1>
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

            @if (session('success'))
                <div class="mt-3 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mt-4 rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#0F3F3B]">
                <div class="relative">
                    <div class="flex items-end mx-0 px-0">
                        <a href="{{ route('persi.pengaturan', ['tab' => 'coa']) }}"
                            class="tab-umk tab-umk--left tab-umk--green"
                            :class="tab === 'coa' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">COA</span>
                        </a>

                        <a href="{{ route('persi.pengaturan', ['tab' => 'kelas']) }}"
                            class="tab-umk tab-umk--mid tab-umk--orange"
                            :class="tab === 'kelas' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text">KELAS RS</span>
                        </a>

                        <a href="{{ route('persi.pengaturan', ['tab' => 'anggota']) }}"
                            class="tab-umk tab-umk--right tab-umk--yellow"
                            :class="tab === 'anggota' ? 'tab-umk--active' : 'tab-umk--inactive'">
                            <span class="tab-umk__text tab-umk__text--dark">ANGGOTA</span>
                        </a>
                    </div>
                    <div class="h-6 md:h-7"
                        :class="tab === 'coa' ? 'bg-[#0F3F3B]' : (tab === 'kelas' ? 'bg-[#E77A2E]' : 'bg-[#F6E88E]')">
                    </div>
                </div>
            </div>

            {{-- COA --}}
            <div x-show="tab === 'coa'" x-cloak
                class="mt-6 rounded-2xl bg-[#0F3F3B] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="rounded-xl bg-[#F5F1E6] p-4 md:p-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-[#0F3F3B]">COA</h2>
                        <button type="button" @click="showCoaCreate = true; toggleBodyLock(true)"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-5 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            + Tambah COA
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="coaTable" class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left text-gray-600">
                                    <th class="py-2">Kode</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Tipe</th>
                                    <th class="py-2">Level</th>
                                    <th class="py-2">Parent</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coaAccounts as $coa)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">{{ $coa->code }}</td>
                                        <td class="py-2">{{ $coa->name }}</td>
                                        <td class="py-2">{{ $coa->type }}</td>
                                        <td class="py-2">{{ $coa->level }}</td>
                                        <td class="py-2">
                                            {{ $coa->parent ? $coa->parent->code . ' - ' . $coa->parent->name : '-' }}
                                        </td>
                                        <td class="py-2">{{ $coa->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                        <td class="py-2">
                                            <div class="flex gap-2">
                                                <button type="button" class="text-blue-700 hover:underline"
                                                    data-id="{{ $coa->id }}" data-code="{{ $coa->code }}"
                                                    data-name="{{ $coa->name }}" data-type="{{ $coa->type }}"
                                                    data-parent-id="{{ $coa->parent_id ?? '' }}"
                                                    data-active="{{ $coa->is_active ? '1' : '0' }}"
                                                    onclick="openCoaEdit(this)">
                                                    Edit
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('persi.pengaturan.coa.toggle', $coa->id) }}">
                                                    @csrf
                                                    <button type="submit" class="text-amber-700 hover:underline">
                                                        {{ $coa->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($coaAccounts->isEmpty())
                                    <tr>
                                        <td colspan="7" class="py-4 text-center text-gray-500">Belum ada COA.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- KELAS RS --}}
            <div x-show="tab === 'kelas'" x-cloak
                class="mt-6 rounded-2xl bg-[#E77A2E] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="rounded-xl bg-[#F5F1E6] p-4 md:p-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-[#0F3F3B]">KELAS RS</h2>
                        <button type="button" @click="showClassCreate = true; toggleBodyLock(true)"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-5 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            + Tambah Kelas
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="classTable" class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left text-gray-600">
                                    <th class="py-2">Kode</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Iuran Default</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($memberClasses as $mc)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">{{ $mc->code }}</td>
                                        <td class="py-2">{{ $mc->name ?? '-' }}</td>
                                        <td class="py-2">Rp {{ number_format((int) $mc->default_dues_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-2">
                                            <div class="flex gap-2">
                                                <button type="button" class="text-blue-700 hover:underline"
                                                    data-id="{{ $mc->id }}" data-code="{{ $mc->code }}"
                                                    data-name="{{ $mc->name ?? '' }}"
                                                    data-dues="{{ (int) $mc->default_dues_amount }}"
                                                    onclick="openClassEdit(this)">
                                                    Edit
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('persi.pengaturan.member_classes.destroy', $mc->id) }}"
                                                    onsubmit="return confirm('Hapus kelas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-700 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($memberClasses->isEmpty())
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada kelas RS.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- ANGGOTA --}}
            <div x-show="tab === 'anggota'" x-cloak
                class="mt-6 rounded-2xl bg-[#F6E88E] p-4 md:p-6 shadow-[0_10px_30px_rgba(0,0,0,0.12)]">
                <div class="rounded-xl bg-[#F5F1E6] p-4 md:p-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="text-lg font-bold text-[#0F3F3B]">ANGGOTA</h2>
                        <button type="button" @click="showMemberCreate = true; toggleBodyLock(true)"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-5 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            + Tambah Anggota
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="memberTable" class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 text-left text-gray-600">
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Nama</th>
                                    <th class="py-2">Kelas</th>
                                    <th class="py-2">Iuran</th>
                                    <th class="py-2">Status</th>
                                    <th class="py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($members as $m)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">{{ $m->persi_code }}</td>
                                        <td class="py-2">{{ $m->name }}</td>
                                        <td class="py-2">{{ $m->memberClass?->code ?? '-' }}</td>
                                        <td class="py-2">Rp {{ number_format((int) $m->dues_amount, 0, ',', '.') }}</td>
                                        <td class="py-2">{{ $m->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                                        <td class="py-2">
                                            <div class="flex gap-2">
                                                <button type="button" class="text-blue-700 hover:underline"
                                                    data-id="{{ $m->id }}" data-persicode="{{ $m->persi_code }}"
                                                    data-name="{{ $m->name }}"
                                                    data-class-id="{{ $m->member_class_id ?? '' }}"
                                                    data-dues="{{ (int) $m->dues_amount }}"
                                                    data-address="{{ $m->address ?? '' }}"
                                                    data-email="{{ $m->email ?? '' }}"
                                                    data-whatsapp="{{ $m->pic_whatsapp ?? '' }}"
                                                    data-active="{{ $m->is_active ? '1' : '0' }}"
                                                    onclick="openMemberEdit(this)">
                                                    Edit
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('persi.pengaturan.members.destroy', $m->id) }}"
                                                    onsubmit="return confirm('Hapus anggota ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-700 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($members->isEmpty())
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-gray-500">Belum ada anggota.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

        {{-- Modal: Create COA --}}
        <template x-teleport="body">
        <div x-show="showCoaCreate" x-cloak x-effect="toggleBodyLock(showCoaCreate)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showCoaCreate = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Tambah COA</h3>
                    <button type="button" class="text-gray-500" @click="showCoaCreate = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form method="POST" action="{{ route('persi.pengaturan.coa.store') }}"
                    class="grid grid-cols-1 gap-4">
                    @csrf
                    <input type="hidden" name="is_active" value="1">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Parent (Opsional)</label>
                        <select name="parent_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                            <option value="">-</option>
                            @foreach ($coaParents as $p)
                                <option value="{{ $p->id }}">
                                    {{ str_repeat('-', max(0, (int) $p->level - 1)) }} {{ $p->code }} - {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

        {{-- Modal: Edit COA --}}
        <template x-teleport="body">
        <div x-show="showCoaEdit" x-cloak x-effect="toggleBodyLock(showCoaEdit)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showCoaEdit = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Edit COA</h3>
                    <button type="button" class="text-gray-500" @click="showCoaEdit = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form id="coa-edit-form" method="POST" action=""
                    data-action-base="{{ url('/persi/pengaturan/coa') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Kode</label>
                        <input type="text" name="code" id="coa-edit-code"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                        <input type="text" name="name" id="coa-edit-name"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Tipe</label>
                        <select name="type" id="coa-edit-type"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                            <option value="income">Penerimaan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Parent (Opsional)</label>
                        <select name="parent_id" id="coa-edit-parent"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                            <option value="">-</option>
                            @foreach ($coaParents as $p)
                                <option value="{{ $p->id }}">
                                    {{ str_repeat('-', max(0, (int) $p->level - 1)) }} {{ $p->code }} - {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" id="coa-edit-active" value="1"
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
        </template>

        {{-- Modal: Create Kelas --}}
        <template x-teleport="body">
        <div x-show="showClassCreate" x-cloak x-effect="toggleBodyLock(showClassCreate)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showClassCreate = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Tambah Kelas RS</h3>
                    <button type="button" class="text-gray-500" @click="showClassCreate = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form method="POST" action="{{ route('persi.pengaturan.member_classes.store') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Kode</label>
                        <input type="text" name="code" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                        <input type="text" name="name" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Iuran Default</label>
                        <input type="number" name="default_dues_amount" value="0"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" min="0" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

        {{-- Modal: Edit Kelas --}}
        <template x-teleport="body">
        <div x-show="showClassEdit" x-cloak x-effect="toggleBodyLock(showClassEdit)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showClassEdit = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Edit Kelas RS</h3>
                    <button type="button" class="text-gray-500" @click="showClassEdit = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form id="class-edit-form" method="POST" action=""
                    data-action-base="{{ url('/persi/pengaturan/member-classes') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Kode</label>
                        <input type="text" name="code" id="class-edit-code"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                        <input type="text" name="name" id="class-edit-name"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Iuran Default</label>
                        <input type="number" name="default_dues_amount" id="class-edit-dues"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" min="0" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

        {{-- Modal: Create Anggota --}}
        <template x-teleport="body">
        <div x-show="showMemberCreate" x-cloak x-effect="toggleBodyLock(showMemberCreate)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showMemberCreate = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-2xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Tambah Anggota</h3>
                    <button type="button" class="text-gray-500" @click="showMemberCreate = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form method="POST" action="{{ route('persi.pengaturan.members.store') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @csrf
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">ID PERSI</label>
                        <input type="text" name="persi_code" value="{{ old('persi_code', $nextPersiCode ?? '') }}"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" readonly>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Kelas RS</label>
                        <select name="member_class_id" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                            <option value="">-</option>
                            @foreach ($memberClassOptions as $mc)
                                <option value="{{ $mc->id }}">{{ $mc->code }} {{ $mc->name ? '- ' . $mc->name : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama RS</label>
                        <input type="text" name="name"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Iuran Real</label>
                        <input type="number" name="dues_amount" value="0"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" min="0">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
                            Aktif
                        </label>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">PIC WA</label>
                        <input type="text" name="pic_whatsapp" class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Alamat</label>
                        <input type="text" name="address"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            SIMPAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>

        {{-- Modal: Edit Anggota --}}
        <template x-teleport="body">
        <div x-show="showMemberEdit" x-cloak x-effect="toggleBodyLock(showMemberEdit)"
            class="fixed inset-0 xl:left-[200px] xl:w-[calc(100%-200px)] z-[100000] flex items-start justify-center overflow-y-auto px-4 pb-8 pt-28 md:pt-32 xl:pt-36">
            <div class="fixed inset-0 bg-gray-400/50" @click="showMemberEdit = false; toggleBodyLock(false)"></div>
            <div class="relative w-[92%] max-w-2xl rounded-2xl bg-white p-6 mt-2">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#0F3F3B]">Edit Anggota</h3>
                    <button type="button" class="text-gray-500" @click="showMemberEdit = false; toggleBodyLock(false)">Tutup</button>
                </div>
                <form id="member-edit-form" method="POST" action=""
                    data-action-base="{{ url('/persi/pengaturan/members') }}"
                    class="grid grid-cols-1 gap-4 md:grid-cols-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">ID PERSI</label>
                        <input type="text" name="persi_code" id="member-edit-persicode"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Nama RS</label>
                        <input type="text" name="name" id="member-edit-name"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" required>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Kelas RS</label>
                        <select name="member_class_id" id="member-edit-class"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                            <option value="">-</option>
                            @foreach ($memberClassOptions as $mc)
                                <option value="{{ $mc->id }}">{{ $mc->code }} {{ $mc->name ? '- ' . $mc->name : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Iuran Real</label>
                        <input type="number" name="dues_amount" id="member-edit-dues"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm" min="0">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" id="member-edit-active" value="1"
                                class="rounded border-gray-300">
                            Aktif
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Alamat</label>
                        <input type="text" name="address" id="member-edit-address"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" id="member-edit-email"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-700">PIC WA</label>
                        <input type="text" name="pic_whatsapp" id="member-edit-whatsapp"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                    </div>
                    <div class="md:col-span-6 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-bold text-white shadow hover:brightness-95">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </template>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/simple-datatables.js') }}"></script>
    <style>
        [x-cloak] {
            display: none !important;
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

        .dataTable-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .dataTable-search {
            margin-left: auto;
            display: flex;
            justify-content: flex-end;
            width: min(360px, 100%);
        }

        .dataTable-search .dataTable-input {
            width: 100%;
            border-radius: 9999px;
            padding: 8px 12px;
            border: 1px solid rgba(15, 63, 59, 0.15);
            background: #fff;
            outline: none;
        }

        .dataTable-selector {
            border-radius: 9999px;
            padding: 6px 10px;
            border: 1px solid rgba(15, 63, 59, 0.15);
            background: #fff;
            outline: none;
        }

        .dataTable-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .dataTable-info {
            font-size: 14px;
            color: #334155;
        }

        .dataTable-pagination {
            margin-left: auto;
        }

        .dataTable-pagination-list {
            display: flex;
            align-items: center;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .dataTable-pagination-list li {
            display: inline-flex;
        }

        .dataTable-pagination-list a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border-radius: 10px;
            border: 1px solid rgba(15, 63, 59, 0.2);
            background: #fff;
            color: #0F3F3B;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            line-height: 1;
        }

        .dataTable-pagination-list li.active a {
            background: #0F3F3B;
            border-color: #0F3F3B;
            color: #fff;
        }

        .dataTable-pagination-list li.ellipsis a {
            border-style: dashed;
        }

        .dataTable-pagination-list a:hover {
            filter: brightness(0.97);
        }

        @media (max-width: 640px) {
            .dataTable-bottom {
                justify-content: center;
            }

            .dataTable-info {
                width: 100%;
                text-align: center;
            }

            .dataTable-pagination {
                margin-left: 0;
            }
        }

        html.dark .dataTable-wrapper {
            color: #e5e7eb;
        }

        html.dark .dataTable-table thead th {
            color: #cbd5e1;
            border-bottom-color: #374151;
        }

        html.dark .dataTable-table tbody td {
            color: #f3f4f6;
            border-bottom-color: #374151;
        }

        html.dark .dataTable-table tbody tr:nth-child(even) td {
            background: rgba(255, 255, 255, .02);
        }

        html.dark .dataTable-table tbody tr:hover td {
            background: rgba(255, 255, 255, .06);
        }

        html.dark .dataTable-search .dataTable-input,
        html.dark .dataTable-selector {
            background: #1f2937;
            border-color: #374151;
            color: #e5e7eb;
        }

        html.dark .dataTable-info {
            color: #cbd5e1;
        }

        html.dark .dataTable-pagination-list a {
            background: #1f2937;
            border-color: #374151;
            color: #e5e7eb;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof simpleDatatables === 'undefined') {
                console.error('simpleDatatables belum ter-load.');
                return;
            }

            const makeTable = (selector, placeholder) => {
                const el = document.querySelector(selector);
                if (!el) return;
                new simpleDatatables.DataTable(el, {
                    searchable: true,
                    perPage: 25,
                    perPageSelect: [10, 25, 50, 100],
                    fixedHeight: false,
                    labels: {
                        placeholder,
                        noRows: 'Belum ada data.',
                        info: 'Menampilkan {start} - {end} dari {rows} data',
                        noResults: 'Data tidak ditemukan',
                    }
                });
            };

            makeTable('#coaTable', 'Cari COA...');
            makeTable('#classTable', 'Cari Kelas RS...');
            makeTable('#memberTable', 'Cari Anggota...');
        });

        function openCoaEdit(btn) {
            const id = btn.dataset.id || '';
            const code = btn.dataset.code || '';
            const name = btn.dataset.name || '';
            const type = btn.dataset.type || 'income';
            const parentId = btn.dataset.parentId || '';
            const active = btn.dataset.active === '1';

            const form = document.getElementById('coa-edit-form');
            const base = form.dataset.actionBase;
            form.action = `${base}/${id}`;

            document.getElementById('coa-edit-code').value = code;
            document.getElementById('coa-edit-name').value = name;
            document.getElementById('coa-edit-type').value = type;
            document.getElementById('coa-edit-parent').value = parentId;
            document.getElementById('coa-edit-active').checked = active;

            const root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.showCoaEdit = true;
                root.__x.$data.toggleBodyLock(true);
            }
        }

        function openClassEdit(btn) {
            const id = btn.dataset.id || '';
            const code = btn.dataset.code || '';
            const name = btn.dataset.name || '';
            const dues = btn.dataset.dues || '0';

            const form = document.getElementById('class-edit-form');
            const base = form.dataset.actionBase;
            form.action = `${base}/${id}`;

            document.getElementById('class-edit-code').value = code;
            document.getElementById('class-edit-name').value = name;
            document.getElementById('class-edit-dues').value = dues;

            const root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.showClassEdit = true;
                root.__x.$data.toggleBodyLock(true);
            }
        }

        function openMemberEdit(btn) {
            const id = btn.dataset.id || '';
            const persiCode = btn.dataset.persicode || '';
            const name = btn.dataset.name || '';
            const classId = btn.dataset.classId || '';
            const dues = btn.dataset.dues || '0';
            const address = btn.dataset.address || '';
            const email = btn.dataset.email || '';
            const whatsapp = btn.dataset.whatsapp || '';
            const active = btn.dataset.active === '1';

            const form = document.getElementById('member-edit-form');
            const base = form.dataset.actionBase;
            form.action = `${base}/${id}`;

            document.getElementById('member-edit-persicode').value = persiCode;
            document.getElementById('member-edit-name').value = name;
            document.getElementById('member-edit-class').value = classId;
            document.getElementById('member-edit-dues').value = dues;
            document.getElementById('member-edit-address').value = address;
            document.getElementById('member-edit-email').value = email;
            document.getElementById('member-edit-whatsapp').value = whatsapp;
            document.getElementById('member-edit-active').checked = active;

            const root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.showMemberEdit = true;
                root.__x.$data.toggleBodyLock(true);
            }
        }
    </script>
@endsection
