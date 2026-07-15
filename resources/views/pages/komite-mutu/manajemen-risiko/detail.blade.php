@extends('layouts.app')

@section('title', 'SIGERCEP - Detail Manajemen Risiko')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Manajemen Risiko</h6>
                    </div>
                    <div class="flex-auto p-6">

                        {{-- Bagian Informasi Utama --}}
                        <h6 class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                            Informasi Utama
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $risiko->unit ?: '-' }}</p>
                            </div>

                            {{-- Risiko --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Risiko</label>
                                <p class="text-slate-600">{{ $risiko->risiko ?: '-' }}</p>
                            </div>

                            {{-- Kode Risiko --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kode Risiko</label>
                                <p class="text-slate-600">{{ $risiko->kode_risiko ?: '-' }}</p>
                            </div>

                            {{-- Sumber Risiko --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Sumber Risiko</label>
                                <p class="text-slate-600">{{ $risiko->sumber_risiko ?: '-' }}</p>
                            </div>

                            {{-- Sebab --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Sebab</label>
                                <p class="text-slate-600">{{ $risiko->sebab ?: '-' }}</p>
                            </div>

                            {{-- Dampak --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Dampak</label>
                                <p class="text-slate-600">{{ $risiko->dampak ?: '-' }}</p>
                            </div>

                            {{-- C / UC --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">C / UC</label>
                                <p class="text-slate-600">{{ $risiko->c_uc ?: '-' }}</p>
                            </div>
                        </div>

                        {{-- Bagian Pengendalian & Analisis --}}
                        <h6 class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                            Pengendalian &amp; Analisis
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                            {{-- Pengendalian --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Pengendalian Yang Ada</label>
                                <p class="text-slate-600 mb-2">{{ $risiko->pengendalian ?: '-' }}</p>
                                <div class="flex gap-2">
                                    @if ($risiko->efektif)
                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                            <i class="fas fa-check mr-1"></i> Efektif
                                        </span>
                                    @endif
                                    @if ($risiko->tidak_efektif)
                                        <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">
                                            <i class="fas fa-times mr-1"></i> Tidak Efektif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Analisis P --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Analisis P</label>
                                <p class="text-slate-600">{{ (float) $risiko->analisis_p ?: '-' }}</p>
                            </div>

                            {{-- Analisis D --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Analisis D</label>
                                <p class="text-slate-600">{{ (float) $risiko->analisis_d ?: '-' }}</p>
                            </div>

                            {{-- Nilai Analisis --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nilai Analisis</label>
                                <p class="text-slate-600">{{ (float) $risiko->analisis_nilai ?: '-' }}</p>
                            </div>

                            {{-- Bobot Analisis --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Bobot Analisis</label>
                                <p class="text-slate-600">{{ (float) $risiko->analisis_bobot ?: '-' }}</p>
                            </div>

                            {{-- Tingkat Risiko Analisis --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tingkat Risiko
                                    Analisis</label>
                                @php
                                    $tingkatAnalisis = $risiko->analisis_tingkat;
                                    $t = strtolower(trim($tingkatAnalisis ?? ''));
                                    $badgeClass = match (true) {
                                        str_contains($t, 'sangat rendah') => 'bg-green-100 text-green-700',
                                        str_contains($t, 'rendah') => 'bg-yellow-100 text-yellow-700',
                                        str_contains($t, 'sangat tinggi') => 'bg-red-200 text-red-800 font-bold',
                                        str_contains($t, 'tinggi') => 'bg-red-100 text-red-700',
                                        str_contains($t, 'sedang') => 'bg-orange-100 text-orange-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $badgeClass }}">
                                    {{ $tingkatAnalisis ?: '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- Bagian Target Waktu --}}
                        <h6 class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                            Target Waktu
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Target Waktu</label>
                                <p class="text-slate-600">{{ $risiko->target_waktu ?: '-' }}</p>
                            </div>
                        </div>

                        {{-- Bagian Mitigasi TW 1-4 --}}
                        @foreach([1, 2, 3, 4] as $tw)
                        <h6 class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                            Tingkat Mitigasi TW {{ $tw }}
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            {{-- Mitigasi P --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Mitigasi P</label>
                                <p class="text-slate-600">{{ (float) $risiko->{'mitigasi_tw'.$tw.'_p'} ?: '-' }}</p>
                            </div>

                            {{-- Mitigasi D --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Mitigasi D</label>
                                <p class="text-slate-600">{{ (float) $risiko->{'mitigasi_tw'.$tw.'_d'} ?: '-' }}</p>
                            </div>

                            {{-- Nilai Mitigasi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nilai Mitigasi</label>
                                <p class="text-slate-600">{{ (float) $risiko->{'mitigasi_tw'.$tw.'_nilai'} ?: '-' }}</p>
                            </div>

                            {{-- Bobot Mitigasi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Bobot Mitigasi</label>
                                <p class="text-slate-600">{{ (float) $risiko->{'mitigasi_tw'.$tw.'_bobot'} ?: '-' }}</p>
                            </div>

                            {{-- Tingkat Risiko Mitigasi --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tingkat Risiko
                                    Mitigasi TW {{ $tw }}</label>
                                @php
                                    $tingkatMitigasi = $risiko->{'mitigasi_tw'.$tw.'_tingkat'};
                                    $tm = strtolower(trim($tingkatMitigasi ?? ''));
                                    $badgeClassM = match (true) {
                                        str_contains($tm, 'sangat rendah') => 'bg-green-100 text-green-700',
                                        str_contains($tm, 'rendah') => 'bg-yellow-100 text-yellow-700',
                                        str_contains($tm, 'sangat tinggi') => 'bg-red-200 text-red-800 font-bold',
                                        str_contains($tm, 'tinggi') => 'bg-red-100 text-red-700',
                                        str_contains($tm, 'sedang') => 'bg-orange-100 text-orange-700',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ $badgeClassM }}">
                                    {{ $tingkatMitigasi ?: '-' }}
                                </span>
                            </div>
                        </div>
                        @endforeach

                        {{-- Tombol Kembali --}}
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
