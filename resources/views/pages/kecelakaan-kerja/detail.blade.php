@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Kecelakaan Kerja</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $k3rs->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $k3rs->unit }}</p>
                            </div>

                            {{-- No. Handphone --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">No. Handphone</label>
                                <p class="text-slate-600">{{ $k3rs->no_hp }}</p>
                            </div>

                            {{-- Jam --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jam</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($k3rs->jam)->format('H:i') }}
                                </p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($k3rs->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Jenis Kecelakaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jenis Kecelakaan</label>
                                <p class="text-slate-600">{{ $k3rs->jenis_kecelakaan }}</p>
                            </div>

                            {{-- Lokasi Kecelakaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lokasi Kecelakaan</label>
                                <p class="text-slate-600">{{ $k3rs->lokasi_kecelakaan }}</p>
                            </div>

                            {{-- Saksi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Saksi</label>
                                <p class="text-slate-600">{{ $k3rs->saksi ?? '-' }}</p>
                            </div>

                            {{-- Kegiatan Yang Dilakukan Saat Kejadian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kegiatan</label>
                                <p class="text-slate-600">{{ $k3rs->kegiatan }}</p>
                            </div>

                            {{-- Riwayat Kecelakaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Riwayat Kecelakaan</label>
                                <p class="text-slate-600">{{ $k3rs->riwayat }}</p>
                            </div>

                            {{-- Penyebab Kecelakaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Penyebab Kecelakaan</label>
                                <p class="text-slate-600">{{ $k3rs->penyebab }}</p>
                            </div>

                            {{-- Nama/Jenis Bahan/Zat Yang Menyebabkan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama/Jenis Bahan/Zat Yang
                                    Menyebabkan</label>
                                <p class="text-slate-600">{{ $k3rs->bahan ?? '-' }}</p>
                            </div>

                            {{-- Bagian Tubuh Yang Cedera --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Bagian Tubuh Yang
                                    Cedera</label>
                                <p class="text-slate-600">{{ $k3rs->cedera }}</p>
                            </div>

                            {{-- Tindakan Pengobatan Pertama Yang Dilakukan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tindakan Pengobatan Pertama
                                    Yang Dilakukan</label>
                                <p class="text-slate-600">{{ $k3rs->pengobatan }}</p>
                            </div>

                            {{-- Tindakan Pengobatan Selanjutnya --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tindakan Pengobatan
                                    Selanjutnya</label>
                                <p class="text-slate-600">{{ $k3rs->pengobatan2 }}</p>
                            </div>

                            {{-- Pelaksana/Pemberi Pengobatan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Pelaksana/Pemberi
                                    Pengobatan</label>
                                <p class="text-slate-600">{{ $k3rs->pelaksana }}</p>
                            </div>

                            {{-- Tanda Tangan Pelapor --}}
                            @if ($k3rs->tanda_tangan)
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan
                                        Pelapor</label>
                                    <img src="{{ asset($k3rs->tanda_tangan) }}" alt="Tanda Tangan Pelapor"
                                        class="border rounded max-w-xs h-auto mt-2">
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan
                                        Pelapor</label>
                                    <p class="text-slate-600 italic">Belum ada tanda tangan.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('kecelakaan-kerja.index') }}"
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
