@extends('layouts.app')

@section('title', 'SIGERCEP - Tambah Change Request')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
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
                                <x-form.select name="permintaan_fitur" label="Permintaan Fitur"
                                    :options="config('units.permintaan_fitur')" :selected="old('permintaan_fitur')" required />

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
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/js/alert-upload.js') }}"></script>
@endpush
