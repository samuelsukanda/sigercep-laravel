@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Komplain IPSRS</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $komplain->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $komplain->unit }}</p>
                            </div>

                            {{-- Tujuan Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Ditujukan Ke Unit</label>
                                <p class="text-slate-600">{{ $komplain->tujuan_unit }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($komplain->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Kendala --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kendala Atau
                                    Pengaduan Di Lapangan</label>
                                <p class="text-slate-600">{{ $komplain->kendala }}</p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status</label>
                                <p class="text-slate-600">{{ $komplain->status ?? '-' }}</p>
                            </div>

                            {{-- Keterangan --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Keterangan</label>
                                <p class="text-slate-600">{{ $komplain->keterangan ?? '-' }}</p>
                            </div>

                            {{-- Foto --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Foto Komplain</label>
                                @if ($komplain->foto)
                                    <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto Barang"
                                        class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-1/2" />
                                @else
                                    <p class="mt-2 text-sm text-slate-600">Tidak ada foto komplain</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komplain.ipsrs.index') }}"
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
