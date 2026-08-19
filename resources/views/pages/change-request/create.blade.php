@extends('layouts.app')

@section('title', 'SIGERCEP - Tambah Change Request')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                @if (!$canRequest)
                    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl overflow-hidden">
                        {{-- Header gradient bar --}}
                        <div class="h-1.5 w-full" style="background: var(--accent);">
                        </div>

                        <div class="flex flex-col items-center text-center px-10 pt-14 pb-16">

                            {{-- Icon lingkaran ungu (terpusat, berdiri sendiri) --}}
                            <div class="flex items-center justify-center pt-4 pb-2 rounded-full mb-5">
                                <i class="fas fa-lock text-2xl" style="color:var(--accent);"></i>
                            </div>

                            {{-- Judul & Subtitle --}}
                            <h5 class="font-bold text-2xl text-slate-800">Akses Tidak Tersedia</h5>
                            <p class="text-sm font-semibold mb-5" style="color:var(--accent);">Change Request</p>

                            {{-- Description --}}
                            <p class="text-sm text-slate-500 max-w-sm leading-relaxed mb-6">
                                Pengajuan <span class="font-semibold text-slate-700">Change Request</span> hanya dapat
                                dilakukan
                                oleh jabatan struktural yang telah terdaftar di menu pengajuan.
                            </p>

                            {{-- Info box --}}
                            <div class="flex items-start gap-3 text-left rounded-xl px-5 py-4 mb-8 w-full max-w-sm"
                                style="background-color:#f5f3ff; border:1px solid #ddd6fe;">
                                <i class="fas fa-info-circle mt-0.5 flex-shrink-0 mr-2" style="color:var(--accent);"></i>
                                <p class="text-xs leading-relaxed" style="color:#5c4ebd;">
                                    Jika Anda merasa memiliki hak akses, silakan hubungi Administrator sistem untuk
                                    mendaftarkan jabatan Anda.
                                </p>
                            </div>

                            {{-- Back button --}}
                            <a href="{{ route('change-request.index') }}"
                                class="inline-flex items-center gap-2 px-8 py-2.5 mb-2 text-sm font-semibold text-white rounded-xl shadow-md hover:opacity-90 transition-all duration-200"
                                style="background-color: var(--accent);">
                                <i class="fas fa-arrow-left text-xs mr-2"></i>
                                Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                @else
                    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Tambah Change Request</h6>
                        </div>
                        <div class="flex-auto p-6">
                            <form id="form" action="{{ route('change-request.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- Info: nama & jabatan otomatis --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-semibold mb-1 text-slate-700">Nama</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-slate-500 text-sm"
                                            value="{{ ucfirst(auth()->user()->name) }}" disabled>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold mb-1 text-slate-700">Jabatan</label>
                                        <input type="text"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-slate-500 text-sm"
                                            value="{{ auth()->user()->jabatan ?? '-' }}" disabled>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    {{-- Permintaan Fitur --}}
                                    <x-form.select name="permintaan_fitur" label="Permintaan Fitur" :options="config('units.permintaan_fitur')"
                                        :selected="old('permintaan_fitur')" required />

                                    {{-- Deskripsi --}}
                                    <div>
                                        <label class="block text-sm font-semibold mb-1 text-slate-700">
                                            Deskripsi
                                        </label>
                                        <textarea name="deskripsi" rows="5"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                            placeholder="Masukan Deskripsi Fitur" required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Upload File Pendukung (PDF) --}}
                                    <x-form.file-upload-pdf name="file_pendukung" label="Upload File Pendukung" />
                                </div>

                                <div class="mt-6">
                                    <x-button.submit>Simpan</x-button.submit>
                                    <a href="{{ route('change-request.index') }}"
                                        class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                        Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/js/alert-upload.js') }}"></script>
@endpush
