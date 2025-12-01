@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Pengajuan Dokumen</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('komite-mutu.pengajuan-dokumen.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Jenis Dokumen --}}
                                <x-form.select name="jenis_dokumen" label="Jenis Dokumen" :options="config('units.jenis_dokumen')"
                                    :selected="old('jenis_dokumen', $pengajuanDokumen->jenis_dokumen ?? '')" required />

                                {{-- Permintaan Pengajuan --}}
                                <x-form.select name="permintaan_pengajuan" label="Permintaan Pengajuan" :options="config('units.permintaan_pengajuan')"
                                    :selected="old(
                                        'permintaan_pengajuan',
                                        $pengajuanDokumen->permintaan_pengajuan ?? '',
                                    )" required />

                                {{-- Kategori Pengajuan --}}
                                <x-form.select name="kategori_pengajuan" label="Kategori Pengajuan" :options="config('units.kategori_pengajuan')"
                                    :selected="old('kategori_pengajuan', $pengajuanDokumen->kategori_pengajuan ?? '')" required />

                                {{-- Nomor Dokumen --}}
                                <x-form.input name="nomor_dokumen"
                                    label="Nomor Dokumen (Diisi Untuk Revisi Dan Pengahpusan)" :value="old('nomor_dokumen', $pengajuanDokumen->nomor_dokumen ?? '')" required />

                                {{-- Judul Dokumen --}}
                                <x-form.input name="judul_dokumen" label="Judul Dokumen" :value="old('judul_dokumen', $pengajuanDokumen->judul_dokumen ?? '')" required />

                                {{-- Nomor Revisi --}}
                                <x-form.input name="nomor_revisi" label="Nomor Revisi" :value="old('nomor_revisi', $pengajuanDokumen->nomor_revisi ?? '')" required />

                                {{-- Alasan Pengajuan --}}
                                <x-form.select name="alasan_pengajuan" label="Alasan Pengajuan" :options="config('units.alasan_pengajuan')"
                                    :selected="old('alasan_pengajuan', $pengajuanDokumen->alasan_pengajuan ?? '')" required />

                                {{-- Bagian Yang Direvisi --}}
                                <x-form.input name="bagian_yang_direvisi" label="Bagian Yang Direvisi (Khusus Revisi)"
                                    :value="old(
                                        'bagian_yang_direvisi',
                                        $pengajuanDokumen->bagian_yang_direvisi ?? '',
                                    )" />

                                {{-- Sebelum Direvisi --}}
                                <x-form.input name="sebelum_revisi" label="Sebelum Direvisi (Khusus Revisi)"
                                    :value="old('sebelum_revisi', $pengajuanDokumen->sebelum_revisi ?? '')" />

                                {{-- Usulan Revisi --}}
                                <x-form.input name="usulan_revisi" label="Usulan Revisi (Khusus Revisi)"
                                    :value="old('usulan_revisi', $pengajuanDokumen->usulan_revisi ?? '')" />

                                {{-- Tanggal Pengajuan --}}
                                <x-form.input name="tanggal_pengajuan" label="Tanggal Pengajuan" :value="old('tanggal_pengajuan', $pengajuanDokumen->tanggal_pengajuan ?? '')"
                                    id="tanggal" placeholder="Pilih Tanggal" required />

                                {{-- Diajukan Oleh --}}
                                <x-form.input name="diajukan_oleh" label="Diajukan Oleh (Pemohon) - Ket. Nama Unit"
                                    :value="old('diajukan_oleh', $pengajuanDokumen->diajukan_oleh ?? '')" required />

                                {{-- Diperiksa Oleh --}}
                                <x-form.input name="diperiksa_oleh" label="Diperiksa Oleh - Ket. Nama Divisi"
                                    :value="old('diperiksa_oleh', $pengajuanDokumen->diperiksa_oleh ?? '')" required />

                                {{-- Disetujui Oleh --}}
                                <x-form.input name="disetujui_oleh" label="Disetujui Oleh - Ket. Nama Departemen"
                                    :value="old('disetujui_oleh', $pengajuanDokumen->disetujui_oleh ?? '')" required />

                                {{-- Lampiran File SPO --}}
                                <x-form.file-upload-pdf name="file_pdf" label="Lampiran File SPO (PDF)" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.pengajuan-dokumen.index') }}"
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
