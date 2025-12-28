@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Laporan Perilaku</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('komite-mutu.laporan-perilaku.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $laporanPerilaku->nama ?? '')" required />

                                {{-- NIK --}}
                                <x-form.input name="nik" label="NIK" :value="old('nik', $laporanPerilaku->nik ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $laporanPerilaku->unit ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $laporanPerilaku->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Kategori Laporan --}}
                                <x-form.select name="kategori_laporan" label="Kategori Laporan" :options="config('units.kategori_laporan')" :selected="old('kategori_laporan', $laporanPerilaku->kategori ?? '')" required />

                                {{-- Keterangan Perilaku--}}
                                <x-form.input name="keterangan_perilaku" label="Keterangan Perilaku" :value="old('keterangan_perilaku', $laporanPerilaku->keterangan_perilaku ?? '')" required />

                                {{-- Dokumen --}}
                                <x-form.file-upload-pdf name="file_pdf" label="Dokumen (PDF)" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.laporan-perilaku.index') }}"
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
