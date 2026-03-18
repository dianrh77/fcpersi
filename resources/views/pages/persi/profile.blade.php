@extends('layouts.app')

@section('content')
    <div
        class="min-h-[calc(100vh-80px)] bg-gradient-to-r from-[#fdebb9] to-[#F5F1E6] dark:from-gray-900 dark:to-gray-900 rounded-xl">
        <div class="mx-auto w-full max-w-full p-4">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between mb-3">
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-extrabold tracking-wide text-[#0F3F3B] dark:text-white">
                        EDIT PROFILE
                    </h1>
                </div>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-[0_10px_30px_rgba(0,0,0,0.12)] bg-[#0F3F3B]">
                <div class="bg-[#F5F1E6] rounded-xl mx-2 my-2 p-4 md:p-6">
                    @if (session('success'))
                        <div
                            class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('persi.profile.update') }}"
                        class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Password Baru (opsional)</label>
                            <input type="password" name="password"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>

                        <div>
                            <label class="mb-1 block text-xs font-semibold text-gray-700">Ulangi Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm">
                        </div>

                        <div class="md:col-span-2 flex justify-end pt-2">
                            <button
                                class="inline-flex items-center rounded-full bg-[#E77A2E] px-6 py-2 text-sm font-extrabold text-white shadow hover:brightness-95">
                                SIMPAN
                            </button>
                        </div>
                    </form>
                </div>

                <div class="px-4 md:px-6 pb-4 md:pb-6 text-center text-xs italic text-white/80">
                    {{-- Hak Cipta Milik Allah Semata --}}
                </div>
            </div>
        </div>
    </div>
@endsection
