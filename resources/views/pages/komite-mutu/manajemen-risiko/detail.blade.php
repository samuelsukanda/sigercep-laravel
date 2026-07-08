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
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            {{-- Col 1: Info & Analisis --}}
                            <div class="flex flex-col gap-6">
                                <div>
                                    <h6
                                        class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                                        Informasi Utama</h6>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <span
                                                class="block text-xs font-semibold text-slate-400 uppercase mb-1">Unit</span>
                                            <span class="block text-sm font-bold text-slate-700">{{ $risiko->unit }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">No
                                                Urut</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ $risiko->no_urut }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <span
                                            class="block text-xs font-semibold text-slate-400 uppercase mb-1">Risiko</span>
                                        <span class="block text-sm text-slate-700">{{ $risiko->risiko ?: '-' }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Kode
                                                Risiko</span>
                                            <span
                                                class="block text-sm text-slate-700">{{ $risiko->kode_risiko ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Sumber
                                                Risiko</span>
                                            <span
                                                class="block text-sm text-slate-700">{{ $risiko->sumber_risiko ?: '-' }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Sebab</span>
                                        <span class="block text-sm text-slate-700">{{ $risiko->sebab ?: '-' }}</span>
                                    </div>

                                    <div class="mb-4">
                                        <span
                                            class="block text-xs font-semibold text-slate-400 uppercase mb-1">Dampak</span>
                                        <span class="block text-sm text-slate-700">{{ $risiko->dampak ?: '-' }}</span>
                                    </div>

                                    <div>
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">C /
                                            UC</span>
                                        <span class="block text-sm text-slate-700">{{ $risiko->c_uc ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Col 2: Pengendalian, Target, Mitigasi --}}
                            <div class="flex flex-col gap-6">
                                <div>
                                    <h6
                                        class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                                        Pengendalian & Analisis</h6>

                                    <div class="mb-4">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Pengendalian
                                            Yang Ada</span>
                                        <span class="block text-sm text-slate-700">{{ $risiko->pengendalian ?: '-' }}</span>
                                        <div class="flex gap-4 mt-2">
                                            @if ($risiko->efektif)
                                                <span
                                                    class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded"><i
                                                        class="fas fa-check mr-1"></i> Efektif</span>
                                            @endif
                                            @if ($risiko->tidak_efektif)
                                                <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded"><i
                                                        class="fas fa-times mr-1"></i> Tidak Efektif</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Analisis
                                                P</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->analisis_p ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Analisis
                                                D</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->analisis_d ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nilai</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->analisis_nilai ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bobot</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->analisis_bobot ?: '-' }}</span>
                                        </div>
                                    </div>

                                    @php
                                        if (!function_exists('getBadgeColor')) {
                                            function getBadgeColor($tingkat)
                                            {
                                                if (empty($tingkat)) {
                                                    return 'bg-slate-100 text-slate-500';
                                                }
                                                $t = strtolower(trim($tingkat));
                                                if (str_contains($t, 'sangat rendah')) {
                                                    return 'bg-green-100 text-green-700';
                                                }
                                                if (str_contains($t, 'rendah')) {
                                                    return 'bg-yellow-100 text-yellow-700';
                                                }
                                                if (str_contains($t, 'sangat tinggi')) {
                                                    return 'bg-red-200 text-red-800 font-bold';
                                                }
                                                if (str_contains($t, 'tinggi')) {
                                                    return 'bg-red-100 text-red-700';
                                                }
                                                if (str_contains($t, 'sedang')) {
                                                    return 'bg-orange-100 text-orange-700';
                                                }
                                                return 'bg-slate-100 text-slate-700';
                                            }
                                        }
                                    @endphp

                                    <div>
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tingkat
                                            Risiko Analisis</span>
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ getBadgeColor($risiko->analisis_tingkat) }}">
                                            {{ $risiko->analisis_tingkat ?: '-' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <h6
                                        class="text-sm font-bold uppercase text-slate-500 border-b border-gray-100 pb-2 mb-4">
                                        Target & Mitigasi</h6>

                                    <div class="mb-4">
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Target
                                            Waktu</span>
                                        <span
                                            class="block text-sm font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded inline-block">{{ $risiko->target_waktu ?: '-' }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Mitigasi
                                                P</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->mitigasi_p ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Mitigasi
                                                D</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->mitigasi_d ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nilai</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->mitigasi_nilai ?: '-' }}</span>
                                        </div>
                                        <div class="bg-gray-50 p-3 rounded-lg text-center border border-gray-100">
                                            <span
                                                class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Bobot</span>
                                            <span
                                                class="block text-sm font-bold text-slate-700">{{ (float) $risiko->mitigasi_bobot ?: '-' }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Tingkat
                                            Risiko Mitigasi</span>
                                        <span
                                            class="inline-block px-3 py-1 text-xs font-bold rounded-full {{ getBadgeColor($risiko->mitigasi_tingkat) }}">
                                            {{ $risiko->mitigasi_tingkat ?: '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 border-t border-gray-100 pt-6">
                            <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
                                class="inline-block px-6 py-2 text-xs font-bold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-lg transition-all">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
