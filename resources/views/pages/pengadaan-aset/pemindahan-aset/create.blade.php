@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Pemindahan Aset</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('pengadaan-aset.pemindahan-aset.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $pengadaan->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $pengadaan->unit ?? '')" required />

                                {{-- Keperluan --}}
                                <x-form.input name="keperluan" label="Keperluan" :value="old('keperluan', $pengadaan->keperluan ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $komplain->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Nama Barang --}}
                                <x-form.input name="nama_barang" label="Nama Barang" :value="old('nama_barang', $pengadaan->nama_barang ?? '')" required />

                                {{-- Tempat Asal Barang --}}
                                <x-form.input name="tempat_asal_barang" label="Tempat Asal Barang" :value="old('tempat_asal_barang', $pengadaan->tempat_asal_barang ?? '')" required />

                                {{-- Foto Barang --}}
                                <x-form.file-upload name="foto_barang" label="Foto Barang"
                                    :current="$pengadaan->foto_barang ?? null" />

                                {{-- Foto Barcode --}}
                                <x-form.file-upload name="foto_barcode" label="Foto Barcode (Jika Ada)"
                                    :current="$pengadaan->foto_barcode ?? null" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('pengadaan-aset.pemindahan-aset.index') }}"
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
    <script src="{{ asset('assets/js/alert-foto-upload.js') }}"></script>
@endpush
