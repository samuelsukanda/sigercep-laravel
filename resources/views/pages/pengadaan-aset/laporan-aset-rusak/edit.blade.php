@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Komplain IPSRS</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komplain.ipsrs.update', $komplain->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input label="Nama" name="nama" value="{{ old('nama', $komplain->nama) }}"
                                    required />

                                {{-- Unit --}}
                                <x-form.select label="Unit" name="unit" :options="config('units.units')" :selected="old('unit', $komplain->unit)"
                                    placeholder="Pilih Unit" required />

                                {{-- Tujuan Unit --}}
                                <x-form.select label="Ditujukan Ke Unit" name="tujuan_unit" :options="config('units.tujuanUnitsIpsrs')"
                                    :selected="old('tujuan_unit', $komplain->tujuan_unit)" placeholder="Pilih Unit" required />

                                {{-- Tanggal --}}
                                <x-form.input label="Tanggal" name="tanggal"
                                    value="{{ old('tanggal', $komplain->tanggal) }}" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Kendala --}}
                                <x-form.textarea label="Kendala atau Pengaduan di Lapangan" name="kendala" rows="5"
                                    required>{{ old('kendala', $komplain->kendala) }}</x-form.textarea>

                                {{-- Foto --}}
                                <x-form.file-upload label="Foto Komplain/Kerusakan/Kendala di Lapangan" name="foto"
                                    preview="{{ $komplain->foto ?? null }}" />

                                {{-- Status --}}
                                <x-form.select label="Status" name="status" :options="['Pending', 'On Progress', 'Done']" :selected="old('status', $komplain->status)"
                                    placeholder="Pilih Status" />

                                {{-- Keterangan --}}
                                <x-form.input label="Keterangan" name="keterangan"
                                    value="{{ old('keterangan', $komplain->keterangan) }}" class="md:col-span-2" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('komplain.ipsrs.index') }}'">
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
