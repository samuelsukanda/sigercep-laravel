@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Visitasi</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $visitasi->nama }}</p>
                            </div>

                            {{-- Tim --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tim</label>
                                <p class="text-slate-600">{{ $visitasi->tim }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($visitasi->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Kendala --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kendala</label>
                                <p class="text-slate-600">{{ $visitasi->kendala }}</p>
                            </div>

                            {{-- Foto --}}
                            @if ($visitasi->foto)
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Foto Komplain</label>
                                    <img src="{{ asset('storage/' . $visitasi->foto) }}" alt="Foto Komplain"
                                        class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-1/2" />
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('visitasi.index') }}"
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
