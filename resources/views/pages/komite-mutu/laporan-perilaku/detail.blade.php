@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Laporan Perilaku</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->nama }}</p>
                            </div>

                            {{-- NIK --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">NIK</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->nik }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->unit }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($laporanPerilaku->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Kategori Laporan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kategori Laporan</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->kategori_laporan }}</p>
                            </div>

                            {{-- Keterangan Perilaku --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Keterangan Perilaku</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->keterangan_perilaku }}</p>
                            </div>

                            {{-- Dokumen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Dokumen (PDF)</label>
                                <p class="text-slate-600">{{ $laporanPerilaku->file_pdf }}</p>
                                <a href="{{ route('laporan-perilaku.show-file', $laporanPerilaku->id) }}" target="_blank"
                                    class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                    📄 Lihat File PDF
                                </a>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komite-mutu.laporan-perilaku.index') }}"
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
