@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Komplain Outsourcing Vendor</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('komplain.outsourcing-vendor.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $komplain->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $komplain->unit ?? '')" required />

                                {{-- Tujuan Unit --}}
                                <x-form.select name="tujuan_unit" label="Ditujukan Ke Unit" :options="config('units.tujuanUnitsOutsourcing')"
                                    :selected="old('tujuan_unit', $komplain->tujuan_unit ?? '')" required />

                                {{-- Jam --}}
                                <x-form.input type="time" name="jam" label="Jam" :value="old('jam', $komplain->jam ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input type="date" name="tanggal" label="Tanggal" :value="old('tanggal', $komplain->tanggal ?? '')" required />

                                {{-- Kendala --}}
                                <x-form.textarea name="kendala" label="Kendala atau Pengaduan di lapangan" :value="old('kendala', $komplain->kendala ?? '')"
                                    required />

                                {{-- Area --}}
                                <x-form.input name="area" label="Area komplain yang dilaporkan:" :value="old('area', $komplain->area ?? '')" required />

                                {{-- Foto --}}
                                <x-form.file-upload name="foto" label="Foto Komplain/Keluhan di Lapangan:"
                                    :current="$komplain->foto ?? null" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komplain.outsourcing-vendor.index') }}"
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
    <script src="{{ asset('assets/js/sweatalert.js') }}"></script>
@endpush
