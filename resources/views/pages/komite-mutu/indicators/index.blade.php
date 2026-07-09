@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Indikator Mutu')

@push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="font-bold text-2xl text-slate-800 dark:text-white">Indikator Mutu</h3>
            </div>

            <div class="flex items-center gap-3">
                @canAccess('mutu', 'update')
                <a href="{{ route('indicator-values.bulk-edit', ['tahun' => $tahun, 'jenis' => $jenis]) }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase rounded-lg shadow-md hover:shadow-lg active:opacity-85 transition-all hover:scale-102"
                    style="background-color: #7664E4 !important; color: white !important;">
                    <i class="fas fa-edit mr-2"></i> Input Capaian Bulanan
                </a>
                @endcanAccess
            </div>
        </div>

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="relative text-sm w-full p-4 mb-4 text-white border border-transparent rounded-lg bg-gradient-to-tl from-red-600 to-orange-600 shadow-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Statistics Row --}}
        <div class="flex flex-wrap -mx-3 mb-6">
            {{-- Card 1: Total Indikator --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Total Indikator ({{ $jenis }})</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $indicators->count() }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500 shadow-soft-2xl">
                                    <i class="fas fa-list-check text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Capaian Diatas Target --}}
            @php
                $totalTargetMet = 0;
                $totalValuesCount = 0;

                foreach ($indicators as $ind) {
                    $tgt = $ind->targets->first()?->target_value;
                    if ($tgt !== null) {
                        foreach ($ind->values as $val) {
                            if ($val->nilai !== null) {
                                $totalValuesCount++;
                                $lowerIsBetter = false;
                                $namaLower = strtolower($ind->nama_indikator);
                                if (
                                    str_contains($namaLower, 'kejadian') ||
                                    str_contains($namaLower, 'kematian') ||
                                    str_contains($namaLower, 'infeksi') ||
                                    str_contains($namaLower, 'keterlambatan') ||
                                    str_contains($namaLower, 'salah') ||
                                    str_contains($namaLower, 'reaksi') ||
                                    str_contains($namaLower, 'insiden') ||
                                    str_contains($namaLower, 'tertusuk') ||
                                    str_contains($namaLower, 'kasus') ||
                                    str_contains($namaLower, 'iddo') ||
                                    str_contains($namaLower, 'isk') ||
                                    str_contains($namaLower, 'iadp') ||
                                    str_contains($namaLower, 'vap') ||
                                    str_contains($namaLower, 'plebitis') ||
                                    $tgt <= 15
                                ) {
                                    $lowerIsBetter = true;
                                }

                                $success = $lowerIsBetter ? $val->nilai <= $tgt : $val->nilai >= $tgt;
                                if ($success) {
                                    $totalTargetMet++;
                                }
                            }
                        }
                    }
                }

                $achievementRate = $totalValuesCount > 0 ? round(($totalTargetMet / $totalValuesCount) * 100, 1) : 0;
            @endphp
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Tingkat Capaian Target</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $achievementRate }}%</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-soft-2xl">
                                    <i class="fas fa-circle-check text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Tahun Monitoring --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Tahun Monitoring</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $tahun }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-orange-500 to-amber-400 shadow-soft-2xl">
                                    <i class="fas fa-calendar-check text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Kategori Indikator --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Kategori</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $jenis }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-500 to-pink-500 shadow-soft-2xl">
                                    <i class="fas fa-bookmark text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter and Table Section --}}
        <div
            class="bg-white dark:bg-slate-850 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800 overflow-hidden mb-8">
            {{-- Filters Card Header --}}
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    {{-- Category Tabs (Normal links to avoid POST bug) --}}
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach ($jenisOptions as $opt)
                            @php
                                $isActive = $jenis === $opt;
                            @endphp
                            <a href="{{ route('indicators.index', ['jenis' => $opt, 'tahun' => $tahun]) }}"
                                class="px-4 py-2 text-xs font-bold rounded-lg transition-all border"
                                style="{{ $isActive
                                    ? 'background-color: #7664E4 !important; color: white !important; border-color: transparent !important; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1) !important;'
                                    : 'background-color: transparent !important; color: #7664E4 !important; border-color: #7664E4 !important;' }} margin-right: 8px !important; margin-bottom: 8px !important;">
                                {{ $opt }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Year Selector --}}
                    <form method="GET" action="{{ route('indicators.index') }}" id="filterForm"
                        class="flex items-center gap-2">
                        <label for="tahunSelect"
                            class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase mr-2">Tahun:</label>
                        <select id="tahunSelect" name="tahun" onchange="this.form.submit()"
                            class="text-xs font-bold bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($tahunOptions as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="jenis" value="{{ $jenis }}">
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto custom-scrollbar p-6">
                <table class="w-full text-left border-collapse border border-gray-300 dark:border-slate-700">
                    <thead>
                        <tr class="text-slate-400 dark:text-slate-300 text-xs font-bold uppercase">
                            <th class="py-3 px-3 text-center w-12 border border-gray-300 dark:border-slate-700">No</th>
                            <th class="py-3 px-4 min-w-[280px] border border-gray-300 dark:border-slate-700">Indikator Mutu
                            </th>
                            <th class="py-3 px-4 min-w-[120px] border border-gray-300 dark:border-slate-700">PJ / Unit</th>
                            <th class="py-3 px-3 text-center min-w-[80px] border border-gray-300 dark:border-slate-700">
                                Target</th>
                            @for ($m = 1; $m <= 12; $m++)
                                <th class="py-3 px-2 text-center w-14 border border-gray-300 dark:border-slate-700">
                                    {{ substr(date('F', mktime(0, 0, 0, $m, 1)), 0, 3) }}
                                </th>
                            @endfor
                            <th
                                class="py-3 px-3 text-center min-w-[80px] bg-slate-50 dark:bg-slate-900/30 border border-gray-300 dark:border-slate-700">
                                Rata-Rata</th>
                            @canAccess('mutu', 'update')
                            <th class="py-3 px-3 text-center w-24 border border-gray-300 dark:border-slate-700">Aksi</th>
                            @endcanAccess
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                        @forelse ($indicators as $indicator)
                            @php
                                $target = $indicator->targets->first()?->target_value;

                                // Deteksi arah target (apakah lebih kecil atau lebih besar yang bagus)
                                $lowerIsBetter = false;
                                $namaLower = strtolower($indicator->nama_indikator);
                                if (
                                    str_contains($namaLower, 'kejadian') ||
                                    str_contains($namaLower, 'kematian') ||
                                    str_contains($namaLower, 'infeksi') ||
                                    str_contains($namaLower, 'keterlambatan') ||
                                    str_contains($namaLower, 'salah') ||
                                    str_contains($namaLower, 'reaksi') ||
                                    str_contains($namaLower, 'insiden') ||
                                    str_contains($namaLower, 'tertusuk') ||
                                    str_contains($namaLower, 'kasus') ||
                                    str_contains($namaLower, 'iddo') ||
                                    str_contains($namaLower, 'isk') ||
                                    str_contains($namaLower, 'iadp') ||
                                    str_contains($namaLower, 'vap') ||
                                    str_contains($namaLower, 'plebitis') ||
                                    $target <= 15
                                ) {
                                    $lowerIsBetter = true;
                                }

                                $valuesByMonth = $indicator->values->keyBy('bulan');
                                $sum = 0;
                                $count = 0;
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                {{-- No Urut --}}
                                <td
                                    class="py-4 px-3 text-center font-bold text-xs text-slate-400 border border-gray-300 dark:border-slate-700">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Nama Indikator --}}
                                <td
                                    class="py-4 px-4 font-semibold text-slate-800 dark:text-slate-200 border border-gray-300 dark:border-slate-700">
                                    {{ $indicator->nama_indikator }}
                                </td>

                                {{-- PJ / Unit --}}
                                <td
                                    class="py-4 px-4 text-xs font-medium text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700">
                                    <span
                                        class="block font-bold text-slate-700 dark:text-slate-300">{{ $indicator->pj }}</span>
                                    @if ($indicator->unit_terkait)
                                        <span
                                            class="block text-[10px] text-slate-400 mt-0.5">{{ $indicator->unit_terkait }}</span>
                                    @endif
                                </td>

                                {{-- Target --}}
                                <td
                                    class="py-4 px-3 text-center font-bold text-slate-700 dark:text-slate-300 border border-gray-300 dark:border-slate-700">
                                    @if ($target !== null)
                                        <span class="text-xs bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-md">
                                            {{ $lowerIsBetter ? '≤' : '≥' }} {{ (float) $target }}%
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>

                                {{-- Capaian Bulanan --}}
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $valObj = $valuesByMonth->get($m);
                                        $nilai = $valObj?->nilai;
                                        $numerator = $valObj?->numerator;
                                        $denominator = $valObj?->denominator;

                                        $cellClass = 'text-slate-400';
                                        if ($nilai !== null) {
                                            $sum += $nilai;
                                            $count++;
                                            $success = $lowerIsBetter ? $nilai <= $target : $nilai >= $target;
                                            $cellClass = $success
                                                ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 dark:text-emerald-400 font-bold'
                                                : 'text-red-500 bg-red-50 dark:bg-red-950/20 dark:text-red-400 font-bold';
                                        }

                                        $tooltipText = '';
                                        if ($numerator !== null && $denominator !== null) {
                                            $tooltipText =
                                                'Numerator: ' .
                                                (float) $numerator .
                                                ' | Denominator: ' .
                                                (float) $denominator;
                                        }
                                    @endphp
                                    <td class="py-3 px-1 text-center text-xs {{ $cellClass }} border border-gray-300 dark:border-slate-700"
                                        title="{{ $tooltipText }}">
                                        @if ($nilai !== null)
                                            <span class="block">{{ (float) $nilai }}%</span>
                                            @if ($numerator !== null && $denominator !== null)
                                                <span class="block text-[9px] font-normal opacity-70 leading-none mt-0.5">
                                                    {{ (float) $numerator }}/{{ (float) $denominator }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="opacity-30 font-light">-</span>
                                        @endif
                                    </td>
                                @endfor

                                {{-- Rata-Rata Capaian --}}
                                @php
                                    $average = $count > 0 ? round($sum / $count, 2) : null;
                                    $avgClass = 'text-slate-400';
                                    if ($average !== null && $target !== null) {
                                        $avgSuccess = $lowerIsBetter ? $average <= $target : $average >= $target;
                                        $avgClass = $avgSuccess
                                            ? 'text-emerald-700 bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 font-extrabold'
                                            : 'text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400 font-extrabold';
                                    }
                                @endphp
                                <td
                                    class="py-4 px-3 text-center text-xs font-bold bg-slate-50/50 dark:bg-slate-900/20 {{ $avgClass }} border border-gray-300 dark:border-slate-700">
                                    @if ($average !== null)
                                        {{ $average }}%
                                    @else
                                        -
                                    @endif
                                </td>

                                @canAccess('mutu', 'update')
                                <td class="py-3 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button"
                                            onclick='openEditModal({{ $indicator->id }}, @json($indicator->nama_indikator), @json($indicator->pj), @json($indicator->unit_terkait), @json($target))'
                                            class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/30 dark:hover:bg-blue-800/50 p-1.5 rounded transition-colors"
                                            title="Edit Indikator">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete({{ $indicator->id }})"
                                            class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-800/50 p-1.5 rounded transition-colors"
                                            title="Hapus Indikator">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                                @endcanAccess
                            </tr>
                        @empty
                            <tr>
                                <td colspan="18"
                                    class="py-12 text-center text-slate-500 border border-gray-300 dark:border-slate-700">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="fas fa-folder-open text-4xl text-slate-300"></i>
                                        <span class="font-bold text-slate-400">Tidak ada data indikator ditemukan untuk
                                            kategori ini.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Form Hapus --}}
        <form id="deleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
@endsection

@canAccess('mutu', 'update')
{{-- Modal Edit Indikator --}}
@push('modals')
    <div id="editIndicatorModal" tabindex="-1" aria-hidden="true" aria-labelledby="modalTitleEdit" role="dialog"
        class="modal-overlay hidden justify-center"
        style="
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.5);
            padding: 2rem 1rem;
            overflow-y: auto;
            align-items: flex-start;
         ">

        {{-- Dialog container --}}
        <div class="relative w-full" style="max-width: 480px; margin: 1.5rem auto;">
            <div
                style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                overflow: hidden;
            ">

                {{-- ── Header ── --}}
                <div
                    style="
                    background: #3b82f6;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                ">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div
                            style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-edit" style="color: #fff; font-size: 13px;"></i>
                        </div>
                        <div>
                            <h3 id="modalTitleEdit"
                                style="
                                margin: 0;
                                font-size: 14px;
                                font-weight: 700;
                                color: #fff;
                                line-height: 1.2;
                            ">
                                Edit Indikator</h3>
                            <p
                                style="
                                margin: 0;
                                font-size: 11px;
                                color: rgba(255,255,255,0.7);
                                margin-top: 1px;
                            ">
                                Kategori: {{ ucfirst(str_replace('-', ' ', $jenis)) }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditModal()" aria-label="Tutup modal"
                        style="
                            width: 30px; height: 30px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.15);
                            border: none;
                            cursor: pointer;
                            display: flex; align-items: center; justify-content: center;
                            transition: background 0.15s;
                            flex-shrink: 0;
                        "
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                    </button>
                </div>

                {{-- ── Body ── --}}
                <form id="editIndicatorForm" method="POST" style="padding: 1.375rem 1.25rem 1.25rem;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <input type="hidden" name="jenis_indikator" value="{{ $jenis }}">

                    {{-- Target --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="edit_target_value"
                            style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            Target (%)
                        </label>
                        <input type="number" step="any" id="edit_target_value" name="target_value"
                            placeholder="e.g. 80"
                            style="
                                width: 100%;
                                box-sizing: border-box;
                                height: 38px;
                                padding: 0 11px;
                                font-size: 13.5px;
                                color: #1e293b;
                                background: #f8fafc;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                outline: none;
                                transition: border-color 0.15s, box-shadow 0.15s;
                            "
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                    </div>

                    {{-- Nama Indikator --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="edit_nama_indikator"
                            style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            Nama Indikator <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="edit_nama_indikator" name="nama_indikator" required
                            style="
                                width: 100%;
                                box-sizing: border-box;
                                height: 38px;
                                padding: 0 11px;
                                font-size: 13.5px;
                                color: #1e293b;
                                background: #f8fafc;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                outline: none;
                                transition: border-color 0.15s, box-shadow 0.15s;
                            "
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                    </div>

                    {{-- PJ & Unit Terkait --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1.375rem;">
                        <div>
                            <label for="edit_pj"
                                style="
                                       display: block;
                                       font-size: 11px;
                                       font-weight: 700;
                                       color: #475569;
                                       text-transform: uppercase;
                                       letter-spacing: 0.06em;
                                       margin-bottom: 5px;
                                   ">
                                PJ (Penanggung Jawab)
                            </label>
                            <input type="text" id="edit_pj" name="pj"
                                style="
                                    width: 100%;
                                    box-sizing: border-box;
                                    height: 38px;
                                    padding: 0 11px;
                                    font-size: 13.5px;
                                    color: #1e293b;
                                    background: #f8fafc;
                                    border: 1px solid #cbd5e1;
                                    border-radius: 8px;
                                    outline: none;
                                    transition: border-color 0.15s, box-shadow 0.15s;
                                "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                        </div>
                        <div>
                            <label for="edit_unit_terkait"
                                style="
                                       display: block;
                                       font-size: 11px;
                                       font-weight: 700;
                                       color: #475569;
                                       text-transform: uppercase;
                                       letter-spacing: 0.06em;
                                       margin-bottom: 5px;
                                   ">
                                Unit Terkait
                            </label>
                            <input type="text" id="edit_unit_terkait" name="unit_terkait"
                                style="
                                    width: 100%;
                                    box-sizing: border-box;
                                    height: 38px;
                                    padding: 0 11px;
                                    font-size: 13.5px;
                                    color: #1e293b;
                                    background: #f8fafc;
                                    border: 1px solid #cbd5e1;
                                    border-radius: 8px;
                                    outline: none;
                                    transition: border-color 0.15s, box-shadow 0.15s;
                                "
                                onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                        </div>
                    </div>

                    {{-- ── Footer / Action buttons ── --}}
                    <div
                        style="
                        border-top: 1px solid #f1f5f9;
                        padding-top: 1rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 8px;
                    ">
                        <button type="button" onclick="closeEditModal()"
                            style="
                                height: 38px;
                                padding: 0 16px;
                                font-size: 13px;
                                font-weight: 600;
                                color: #64748b;
                                background: #f1f5f9;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: background 0.15s;
                            "
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            Batal
                        </button>
                        <button type="submit"
                            style="
                                height: 38px;
                                padding: 0 20px;
                                font-size: 13px;
                                font-weight: 700;
                                color: #fff;
                                background: #3b82f6;
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 7px;
                                transition: background 0.15s, box-shadow 0.15s;
                            "
                            onmouseover="this.style.background='#2563eb'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.35)'"
                            onmouseout="this.style.background='#3b82f6'; this.style.boxShadow='none'">
                            <i class="fas fa-save" style="font-size: 12px;"></i>
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-swal-confirm {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-confirm:hover {
            background-color: #dc2626 !important;
        }

        .btn-swal-cancel {
            background-color: #6b7280 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-cancel:hover {
            background-color: #4b5563 !important;
        }
    </style>
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEditModal();
        });

        function openEditModal(id, nama, pj, unit, target) {
            const modal = document.getElementById('editIndicatorModal');
            if (!modal) return;

            // Set form values
            let url = "{{ route('indicators.update', ':id') }}";
            url = url.replace(':id', id);
            document.getElementById('editIndicatorForm').action = url;

            document.getElementById('edit_nama_indikator').value = nama || '';
            document.getElementById('edit_pj').value = pj || '';
            document.getElementById('edit_unit_terkait').value = unit || '';
            document.getElementById('edit_target_value').value = target || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstInput) firstInput.focus();
            }, 50);
        }

        function closeEditModal() {
            const modal = document.getElementById('editIndicatorModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                },
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    let url = "{{ route('indicators.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>
@endpush
@endcanAccess
