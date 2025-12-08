@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Pemindahan Aset</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('pengadaan-aset.pemindahan-aset.update', $pengadaan->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $pengadaan->nama ?? '')" required />

                                {{-- Unit Asal --}}
                                <x-form.select name="unit_asal" label="Unit Asal" :options="config('units.units')" :selected="old('unit_asal', $pengadaan->unit_asal ?? '')"
                                    required />

                                {{-- Unit Tujuan --}}
                                <x-form.select name="unit_tujuan" label="Unit Tujuan" :options="config('units.units')" :selected="old('unit_tujuan', $pengadaan->unit_tujuan ?? '')"
                                    required />

                                {{-- Keperluan --}}
                                <x-form.input name="keperluan" label="Keperluan" :value="old('keperluan', $pengadaan->keperluan ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input label="Tanggal" name="tanggal"
                                    value="{{ old('tanggal', $pengadaan->tanggal) }}" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Nama Barang --}}
                                <x-form.input name="nama_barang" label="Nama Barang" :value="old('nama_barang', $pengadaan->nama_barang ?? '')" required />

                                {{-- Foto Barang --}}
                                <x-form.file-upload name="foto_barang" label="Foto Barang"
                                    preview="{{ $pengadaan->foto_barang ?? null }}" />

                                {{-- Foto Barcode --}}
                                <x-form.file-upload name="foto_barcode" label="Foto Barcode (Jika Ada)"
                                    preview="{{ $pengadaan->foto_barcode ?? null }}" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('pengadaan-aset.pemindahan-aset.index') }}'">
                                    Batal
                                </x-button.cancel>
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
