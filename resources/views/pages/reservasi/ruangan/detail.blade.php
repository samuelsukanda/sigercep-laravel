@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Reservasi Ruangan</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $reservasi->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $reservasi->unit }}</p>
                            </div>

                            {{-- Jam Mulai --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jam Mulai</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $reservasi->jam_mulai)->format('H:i') }}
                            </div>

                            {{-- Jam Selesai --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jam Selesai</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $reservasi->jam_selesai)->format('H:i') }}
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Ruang --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Ruang</label>
                                <p class="text-slate-600">{{ $reservasi->ruang }}</p>
                            </div>

                            {{-- Approval --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Approval</label>
                                <p class="text-slate-600">{{ $reservasi->approval }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('reservasi.ruangan.index') }}"
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
