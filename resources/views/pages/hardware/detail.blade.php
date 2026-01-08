@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Ceklis Hardware</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $hardware->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $hardware->unit }}</p>
                            </div>

                            {{-- Lantai --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lantai</label>
                                <p class="text-slate-600">{{ $hardware->lantai }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($hardware->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Table Checklist Hardware --}}
                            <div class="mt-4">
                                <table class="w-full border border-gray-400 text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="border border-gray-400 px-3 py-2 text-center">No</th>
                                            <th class="border border-gray-400 px-3 py-2 text-center">Tindakan</th>
                                            <th class="border border-gray-400 px-3 py-2 text-center">Cek</th>
                                            <th class="border border-gray-400 px-3 py-2 text-center">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $checklistItems = [
                                                'Wallpaper backround RS',
                                                'Pastikan login sistem operasi ada dua (admin dan limit)',
                                                'Pastikan password admin dan limit terkontrol',
                                                'Screen saver jalan',
                                                'Aplikasi remote VNC berjalan',
                                                'Bersihkan komputer dari software yang tidak diijinkan',
                                                'Cek kapasitas hardisk sistem operasi C',
                                                'Jalankan SIMRS HAMORI',
                                                'IP address sesuai',
                                                'Ping Local dan Internet berjalan/reply',
                                                'Printer bisa digunakan',
                                                'Catridge terisi tinta',
                                                'Cek nyalanya Monitor',
                                                'Cek fungsi UPS',
                                                'Cek fungsi Mouse',
                                                'Cek fungsi Keyboard',
                                                'Bersihkan Casing bagian dalam dari debu',
                                                'Rapihkan pengkabelan',
                                                'Rapikan penempatan',
                                                'Akses Flashdisk terkontrol',
                                            ];

                                            $checklistData = $hardware->checklist ?? [];
                                        @endphp

                                        @foreach ($checklistItems as $index => $item)
                                            @php
                                                $status = $checklistData[$item]['status'] ?? 0;
                                                $keterangan = $checklistData[$item]['keterangan'] ?? '-';
                                            @endphp
                                            <tr>
                                                <td class="border border-gray-400 px-3 py-2 text-center">{{ $index + 1 }}
                                                </td>
                                                <td class="border border-gray-400 px-3 py-2">{{ $item }}</td>
                                                <td class="border border-gray-400 px-3 py-2 text-center">
                                                    {!! $status ? '<span class="text-green-600 text-lg">✅</span>' : '<span class="text-red-600 text-lg">❌</span>' !!}
                                                </td>
                                                <td class="border border-gray-400 px-3 py-2">
                                                    {{ $keterangan }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Tanda Tangan --}}
                            @if ($hardware->tanda_tangan)
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan</label>
                                    <img src="{{ asset($hardware->tanda_tangan) }}" alt="Tanda Tangan"
                                        class="border rounded max-w-xs h-auto mt-2">
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan</label>
                                    <p class="text-slate-600 italic">Belum ada tanda tangan.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('hardware.index') }}"
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
