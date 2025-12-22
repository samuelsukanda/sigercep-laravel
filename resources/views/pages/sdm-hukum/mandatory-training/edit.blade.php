@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Mandatory Training</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('sdm-hukum.mandatory-training.update', $mandatoryTraining->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama File --}}
                                <x-form.input name="file_pdf" label="Nama File" :value="old('file_pdf', $mandatoryTraining->file_pdf ?? '')" required disabled/>

                                {{-- File PDF --}}
                                <x-form.file-upload-pdf name="file_pdf"
                                    label="Upload File (PDF - Kosongkan jika tidak diubah)" />

                                @if ($mandatoryTraining->file_path)
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold mb-2 text-slate-700">File Sekarang</label>
                                        <p class="text-slate-600">{{ $mandatoryTraining->file_pdf }}</p>
                                        <a href="{{ route('mandatory-training.show-file', $mandatoryTraining->id) }}" target="_blank"
                                            class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                            📄 Lihat File PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <a href="{{ route('sdm-hukum.mandatory-training.index') }}"
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
