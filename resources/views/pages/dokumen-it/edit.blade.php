@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Dokumen IT')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Dokumen IT</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('dokumen-it.update', $DokumenIt->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama File --}}
                                <x-form.input name="file_pdf" label="Nama File" :value="old('file_pdf', $DokumenIt->file_pdf ?? '')" required disabled />

                                {{-- Jenis Dokumen --}}
                                <x-form.select name="jenis_dokumen" label="Jenis Dokumen" :options="config('units.jenis_dokumen_it')"
                                    :selected="old('jenis_dokumen', $DokumenIt->jenis_dokumen)" required />

                                {{-- File PDF --}}
                                <x-form.file-upload-pdf name="file_pdf"
                                    label="Upload File (PDF - Kosongkan jika tidak diubah)" />

                                @if ($DokumenIt->file_path)
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold mb-2 text-slate-700">File Sekarang</label>
                                        <a href="{{ route('dokumen-it.show-file', $DokumenIt->id) }}" target="_blank"
                                            class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                            📄 Lihat File PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <a href="{{ route('dokumen-it.index') }}"
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
@endpush
