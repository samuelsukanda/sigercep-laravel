@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Bank SPO</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komite-mutu.bank-spo.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.spo')" :selected="old('unit')" required />

                                {{-- Jenis SPO --}}
                                <x-form.select name="jenis_spo" label="Jenis SPO" :options="['SPO Utama' => 'SPO Utama', 'SPO Terkait' => 'SPO Terkait']" :selected="old('jenis_spo')"
                                    required />

                                {{-- File PDF --}}
                                <x-form.file-upload-pdf name="nama_file" label="Upload File (PDF)" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.bank-spo.index') }}"
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
