@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Peminjaman</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $peminjaman->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $peminjaman->unit }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Nama Barang --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Alat/Barang Yang Di
                                    Pinjam</label>
                                <p class="text-slate-600">{{ $peminjaman->barang }}</p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status</label>
                                <p class="text-slate-600">{{ $peminjaman->status ?? '-' }}</p>
                            </div>

                            {{-- Tanda Tangan --}}
                            @if ($peminjaman->tanda_tangan)
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan
                                    </label>
                                    <img src="{{ asset($peminjaman->tanda_tangan) }}" alt="Tanda Tangan Pelapor"
                                        class="border rounded max-w-xs h-auto mt-2">
                                </div>
                            @else
                                <div class="mb-4">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanda Tangan
                                    </label>
                                    <p class="text-slate-600 italic">Belum ada tanda tangan.</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('peminjaman.index') }}"
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
