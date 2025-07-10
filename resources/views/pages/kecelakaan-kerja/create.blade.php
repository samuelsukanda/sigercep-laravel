@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Kecelakaan Kerja</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('kecelakaan-kerja.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $k3rs->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $k3rs->unit ?? '')" required />

                                {{-- No. Handphone --}}
                                <x-form.input name="no_hp" label="No. Handphone" type="number" :value="old('no_hp', $k3rs->no_hp ?? '')"
                                    required />

                                {{-- Jam --}}
                                <x-form.input type="time" name="jam" label="Jam" :value="old('jam', $k3rs->jam ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $k3rs->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Jenis Kecelakaan --}}
                                <x-form.input name="jenis_kecelakaan" label="Jenis Kecelakaan" :value="old('jenis_kecelakaan', $k3rs->jenis_kecelakaan ?? '')" required />

                                {{-- Lokasi Kecelakaan --}}
                                <x-form.input name="lokasi_kecelakaan" label="Lokasi Kecelakaan" :value="old('lokasi_kecelakaan', $k3rs->lokasi_kecelakaan ?? '')"
                                    required />

                                {{-- Saksi --}}
                                <x-form.input name="saksi" label="Saksi (Jika Ada)" :value="old('saksi', $k3rs->saksi ?? '')" />

                                {{-- Kegiatan Yang Dilakukan Saat Kejadian --}}
                                <x-form.input name="kegiatan" label="Kegiatan Yang Dilakukan Saat Kejadian"
                                    :value="old('kegiatan', $k3rs->kegiatan ?? '')" required />

                                {{-- Riwayat Kecelakaan --}}
                                <x-form.input name="riwayat" label="Riwayat Kecelakaan" :value="old('riwayat', $k3rs->riwayat ?? '')" required />

                                {{-- Penyebab Kecelakaan --}}
                                <x-form.input name="penyebab" label="Penyebab Kecelakaan" :value="old('penyebab', $k3rs->penyebab ?? '')" required />

                                {{-- Nama/Jenis Bahan/Zat Yang Menyebabkan --}}
                                <x-form.input name="bahan" label="Nama/Jenis Bahan/Zat Yang Menyebabkan (Jika Ada)"
                                    :value="old('bahan', $k3rs->bahan ?? '')" />

                                {{-- Bagian Tubuh Yang Cedera --}}
                                <x-form.input name="cedera" label="Bagian Tubuh Yang Cedera" :value="old('cedera', $k3rs->cedera ?? '')" required />

                                {{-- Tindakan Pengobatan Pertama Yang Dilakukan --}}
                                <x-form.input name="pengobatan" label="Tindakan Pengobatan Pertama Yang Dilakukan"
                                    :value="old('pengobatan', $k3rs->pengobatan ?? '')" required />

                                {{-- Tindakan Pengobatan Selanjutnya --}}
                                <x-form.input name="pengobatan2" label="Tindakan Pengobatan Selanjutnya" :value="old('pengobatan2', $k3rs->pengobatan2 ?? '')"
                                    required />

                                {{-- Pelaksana/Pemberi Pengobatan --}}
                                <x-form.input name="pelaksana" label="Pelaksana/Pemberi Pengobatan" :value="old('pelaksana', $k3rs->pelaksana ?? '')"
                                    required />

                                {{-- Tanda Tangan Pelapor --}}
                                <div class="mt-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanda Tangan
                                        Pelapor:</label>
                                    <div class="border rounded shadow-sm bg-white p-4">
                                        <canvas id="signature-pad" class="w-1/2 h-52 rounded"
                                            style="border: 2px solid #9e9e9e;"></canvas>
                                        <input type="hidden" name="tanda_tangan" id="tanda_tangan">
                                        <div class="mt-4 flex gap-2">
                                            <button type="button" id="undo"
                                                class="relative p-4 mb-4 mr-1 text-white border border-solid rounded-lg bg-gradient-to-tl from-zinc-800 to-zinc-700 border-slate-100 px-4 py-2 flex items-center gap-2">
                                                <i class="fa fa-undo mr-1"></i> Undo
                                            </button>
                                            <button type="button" id="clear"
                                                class="relative p-4 mb-4 text-white border border-red-300 border-solid rounded-lg bg-gradient-to-tl from-red-600 to-orange-600 px-4 py-2 flex items-center gap-2">
                                                <i class="fa fa-trash mr-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('kecelakaan-kerja.index') }}"
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
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('assets/js/signature-create.js') }}"></script>
@endpush
