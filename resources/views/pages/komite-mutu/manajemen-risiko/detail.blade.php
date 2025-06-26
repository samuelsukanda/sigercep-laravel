@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Manajemen Risiko</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $mutu->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $mutu->unit }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($mutu->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Uraian Risiko --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Uraian Risiko</label>
                                <p class="text-slate-600">{{ $mutu->uraian }}</p>
                            </div>

                            {{-- Dampak --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Dampak (D)</label>
                                <p class="text-slate-600">{{ $mutu->dampak }}</p>
                            </div>

                            {{-- Kemungkinan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kemungkinan (K)</label>
                                <p class="text-slate-600">{{ $mutu->kemungkinan }}</p>
                            </div>

                            {{-- Nilai Risiko --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nilai Risiko (D x K)</label>
                                <p class="text-slate-600">{{ $mutu->nilai }}</p>
                            </div>

                            {{-- Keterangan --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Keterangan Monitoring
                                    Risiko</label>
                                <p class="text-slate-600">{{ $mutu->keterangan }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
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
