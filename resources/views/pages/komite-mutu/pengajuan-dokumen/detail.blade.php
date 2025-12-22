@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Pengajuan Dokumen</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Jenis Dokumen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jenis Dokumen</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->jenis_dokumen }}</p>
                            </div>

                            {{-- Permintaan Pengajuan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Permintaan Pengajuan</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->permintaan_pengajuan }}</p>
                            </div>

                            {{-- Kategori Pengajuan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kategori Pengajuan</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->kategori_pengajuan }}</p>
                            </div>

                            {{-- Nomor Dokumen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nomor Dokumen</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->nomor_dokumen }}</p>
                            </div>

                            {{-- Judul Dokumen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Judul Dokumen</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->judul_dokumen }}</p>
                            </div>

                            {{-- Nomor Revisi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nomor Revisi</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->nomor_revisi }}</p>
                            </div>

                            {{-- Alasan Pengajuan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Alasan Pengajuan</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->alasan_pengajuan }}</p>
                            </div>

                            {{-- Bagian Yang Direvisi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Bagian Yang Direvisi (Khusus
                                    Revisi)
                                </label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->bagian_yang_direvisi ?? '-' }}</p>
                            </div>

                            {{-- Sebelum Direvisi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Sebelum Direvisi (Khusus
                                    Revisi)</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->sebelum_revisi ?? '-' }}</p>
                            </div>

                            {{-- Usulan Revisi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Usulan Revisi (Khusus
                                    Revisi)</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->usulan_revisi ?? '-' }}</p>
                            </div>

                            {{-- Tanggal Pengajuan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Pengajuan</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($pengajuanDokumen->tanggal_pengajuan)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Diajukan Oleh --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Diajukan Oleh</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->diajukan_oleh }}</p>
                            </div>

                            {{-- Diperiksa Oleh --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Diperiksa Oleh</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->diperiksa_oleh }}</p>
                            </div>

                            {{-- Disetujui Oleh --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Disetujui Oleh</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->disetujui_oleh }}</p>
                            </div>

                            {{-- File SPO --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">File SPO</label>
                                <p class="text-slate-600">{{ $pengajuanDokumen->file_pdf }}</p>
                                <a href="{{ route('pengajuan-dokumen.show-file', $pengajuanDokumen->id) }}" target="_blank"
                                    class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                    📄 Lihat File PDF
                                </a>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komite-mutu.pengajuan-dokumen.index') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
