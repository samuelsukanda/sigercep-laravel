@extends('layouts.app')

@section('title', 'SIGERCEP - Input Capaian Bulanan')

@push('styles')
    <style>
        @media (min-width: 1280px) {
            .modal-overlay {
                left: 17rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="font-bold text-2xl text-gray-900 dark:text-white">Input Capaian Bulanan</h3>
            </div>

            <div>
                <a href="{{ route('indicators.index', ['tahun' => $tahun, 'jenis' => $jenis]) }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-lg active:opacity-85 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>

        @if (session('success'))
            <div
                class="relative text-sm w-full p-4 mb-4 text-white border border-transparent rounded-lg bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-md">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
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

        {{-- Selection Form Card --}}
        <div class="bg-white dark:bg-slate-850 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800 p-6 mb-6">
            <form method="GET" action="{{ route('indicator-values.bulk-edit') }}" id="selectionForm"
                class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-filter text-slate-400"></i>
                    <h6 class="text-sm font-bold text-slate-800 dark:text-white uppercase m-0 ml-2">Pilih Periode & Kategori
                    </h6>
                </div>
                <div class="flex flex-wrap md:flex-nowrap items-center gap-4 flex-1 md:justify-end">
                    {{-- Jenis Select --}}
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap mr-2">Kategori:</label>
                        <select name="jenis" onchange="this.form.submit()"
                            class="text-xs font-bold bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($jenisOptions as $opt)
                                <option value="{{ $opt }}" {{ $jenis === $opt ? 'selected' : '' }}>
                                    {{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Bulan Select --}}
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap mr-2 ml-2">Bulan:</label>
                        <select name="bulan" onchange="this.form.submit()"
                            class="text-xs font-bold bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($bulanNama as $bIdx => $bName)
                                <option value="{{ $bIdx }}" {{ $bulan == $bIdx ? 'selected' : '' }}>
                                    {{ $bName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tahun Select --}}
                    <div class="flex items-center gap-2">
                        <label class="text-xs font-bold text-slate-500 uppercase whitespace-nowrap mr-2 ml-2">Tahun:</label>
                        <select name="tahun" onchange="this.form.submit()"
                            class="text-xs font-bold bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach ($tahunOptions as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </form>
        </div>

        {{-- Bulk Input Form Table Card --}}
        <div
            class="bg-white dark:bg-slate-850 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800 overflow-hidden mb-8">
            <div
                class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex justify-between items-center">
                <span class="text-sm font-bold text-slate-800 dark:text-white uppercase">
                    Form Input: {{ $jenis }} - {{ $bulanNama[$bulan] }} {{ $tahun }}
                </span>
                <button type="button" onclick="openAddModal()"
                    class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold uppercase rounded-lg shadow-md hover:shadow-lg active:opacity-85 transition-all text-white"
                    style="background-color: #7664E4 !important; color: white !important;">
                    <i class="fas fa-plus mr-2"></i> Tambah Indikator
                </button>
            </div>

            <form method="POST" action="{{ route('indicator-values.bulk-update') }}" class="p-6">
                @csrf
                <input type="hidden" name="tahun" value="{{ $tahun }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-left border-collapse border border-gray-300 dark:border-slate-700">
                        <thead>
                            <tr class="text-slate-400 dark:text-slate-300 text-xs font-bold uppercase">
                                <th class="py-3 px-3 text-center w-12 border border-gray-300 dark:border-slate-700">No</th>
                                <th class="py-3 px-4 min-w-[280px] border border-gray-300 dark:border-slate-700">Indikator
                                    Mutu</th>
                                <th class="py-3 px-3 text-center min-w-[80px] border border-gray-300 dark:border-slate-700">
                                    Target</th>
                                <th class="py-3 px-4 w-44 text-center border border-gray-300 dark:border-slate-700">
                                    Numerator (N)</th>
                                <th class="py-3 px-4 w-44 text-center border border-gray-300 dark:border-slate-700">
                                    Denominator (D)</th>
                                <th class="py-3 px-4 w-44 text-center border border-gray-300 dark:border-slate-700">Capaian
                                    (%)</th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                            @forelse ($indicators as $indicator)
                                @php
                                    $target = $indicator->targets->first()?->target_value;

                                    // Cari nilai existing
                                    $valObj = $existing->get($indicator->id);
                                    $num = $valObj?->numerator;
                                    $den = $valObj?->denominator;
                                    $nil = $valObj?->nilai;

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
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors py-3 row-indicator"
                                    data-id="{{ $indicator->id }}">
                                    {{-- No --}}
                                    <td
                                        class="py-4 px-3 text-center font-bold text-xs text-slate-400 border border-gray-300 dark:border-slate-700">
                                        {{ $indicator->no_urut }}
                                    </td>

                                    {{-- Nama Indikator --}}
                                    <td
                                        class="py-4 px-4 font-semibold text-slate-800 dark:text-slate-200 border border-gray-300 dark:border-slate-700">
                                        <span class="block">{{ $indicator->nama_indikator }}</span>
                                        <span class="block text-[10px] text-slate-400 font-medium mt-0.5">PJ:
                                            {{ $indicator->pj }} @if ($indicator->unit_terkait)
                                                | {{ $indicator->unit_terkait }}
                                            @endif
                                        </span>
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

                                    {{-- Numerator Input --}}
                                    <td class="py-3 px-4 border border-gray-300 dark:border-slate-700">
                                        <input type="number" step="any" name="values[{{ $indicator->id }}][numerator]"
                                            value="{{ $num !== null ? (float) $num : '' }}"
                                            class="w-full text-xs p-2 rounded-lg border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-center input-num">
                                    </td>

                                    {{-- Denominator Input --}}
                                    <td class="py-3 px-4 border border-gray-300 dark:border-slate-700">
                                        <input type="number" step="any"
                                            name="values[{{ $indicator->id }}][denominator]"
                                            value="{{ $den !== null ? (float) $den : '' }}"
                                            class="w-full text-xs p-2 rounded-lg border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-center input-den">
                                    </td>

                                    {{-- Capaian (%) Input --}}
                                    <td class="py-3 px-4 border border-gray-300 dark:border-slate-700">
                                        <input type="number" step="any" name="values[{{ $indicator->id }}][nilai]"
                                            value="{{ $nil !== null ? (float) $nil : '' }}"
                                            class="w-full text-xs p-2 rounded-lg border border-gray-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-center font-bold input-nilai">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
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

                @if ($indicators->isNotEmpty())
                    <div class="flex justify-end gap-3 mt-4 border-t border-gray-100 dark:border-slate-800 pt-6">
                        <x-button.submit type="submit">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </x-button.submit>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

{{-- Modal Tambah Indikator --}}
@push('modals')
    <div id="addIndicatorModal"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="modalTitle"
         role="dialog"
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
            <div style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                overflow: hidden;
            ">

                {{-- ── Header ── --}}
                <div style="
                    background: #7664E4;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                ">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-chart-line" style="color: #fff; font-size: 13px;"></i>
                        </div>
                        <div>
                            <h3 id="modalTitle" style="
                                margin: 0;
                                font-size: 14px;
                                font-weight: 700;
                                color: #fff;
                                line-height: 1.2;
                            ">Tambah Indikator Baru</h3>
                            <p style="
                                margin: 0;
                                font-size: 11px;
                                color: rgba(255,255,255,0.7);
                                margin-top: 1px;
                            ">Kategori: {{ ucfirst(str_replace('-', ' ', $jenis)) }}</p>
                        </div>
                    </div>
                    <button type="button"
                        onclick="closeAddModal()"
                        aria-label="Tutup modal"
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
                <form action="{{ route('indicators.store') }}"
                      method="POST"
                      style="padding: 1.375rem 1.25rem 1.25rem;">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <input type="hidden" name="jenis_indikator" value="{{ $jenis }}">

                    {{-- No Urut & Target --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1rem;">
                        <div>
                            <label for="no_urut"
                                   style="
                                       display: block;
                                       font-size: 11px;
                                       font-weight: 700;
                                       color: #475569;
                                       text-transform: uppercase;
                                       letter-spacing: 0.06em;
                                       margin-bottom: 5px;
                                   ">
                                No Urut
                            </label>
                            <input
                                type="text"
                                id="no_urut"
                                name="no_urut"
                                placeholder="e.g. 1, 2a, dst"
                                value="{{ old('no_urut') }}"
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
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                            />
                        </div>
                        <div>
                            <label for="target_value"
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
                            <input
                                type="number"
                                step="any"
                                id="target_value"
                                name="target_value"
                                placeholder="e.g. 80"
                                value="{{ old('target_value') }}"
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
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                            />
                        </div>
                    </div>

                    {{-- Nama Indikator --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="nama_indikator"
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
                        <input
                            type="text"
                            id="nama_indikator"
                            name="nama_indikator"
                            required
                            value="{{ old('nama_indikator') }}"
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
                            onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                        />
                    </div>

                    {{-- PJ & Unit Terkait --}}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1.375rem;">
                        <div>
                            <label for="pj"
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
                            <input
                                type="text"
                                id="pj"
                                name="pj"
                                value="{{ old('pj') }}"
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
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                            />
                        </div>
                        <div>
                            <label for="unit_terkait"
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
                            <input
                                type="text"
                                id="unit_terkait"
                                name="unit_terkait"
                                value="{{ old('unit_terkait') }}"
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
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                            />
                        </div>
                    </div>

                    {{-- ── Footer / Action buttons ── --}}
                    <div style="
                        border-top: 1px solid #f1f5f9;
                        padding-top: 1rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 8px;
                    ">
                        <button type="button"
                            onclick="closeAddModal()"
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
                            onmouseover="this.style.background='#e2e8f0'"
                            onmouseout="this.style.background='#f1f5f9'">
                            Batal
                        </button>
                        <button type="submit"
                            style="
                                height: 38px;
                                padding: 0 20px;
                                font-size: 13px;
                                font-weight: 700;
                                color: #fff;
                                background: #7664E4;
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 7px;
                                transition: background 0.15s, box-shadow 0.15s;
                            "
                            onmouseover="this.style.background='#6453d4'; this.style.boxShadow='0 4px 12px rgba(118,100,228,0.35)'"
                            onmouseout="this.style.background='#7664E4'; this.style.boxShadow='none'">
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
    <script>
        function openAddModal() {
            const modal = document.getElementById('addIndicatorModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const firstInput = modal.querySelector('input:not([type="hidden"]), select, textarea');
                if (firstInput) firstInput.focus();
            }, 50);
        }

        function closeAddModal() {
            const modal = document.getElementById('addIndicatorModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('addIndicatorModal');
            if (modal) {
                document.body.appendChild(modal);

                modal.addEventListener('click', function(e) {
                    if (e.target === this) closeAddModal();
                });
            }

            // Auto-calculate percentage
            const rows = document.querySelectorAll('.row-indicator');

            rows.forEach(row => {
                const numInput = row.querySelector('.input-num');
                const denInput = row.querySelector('.input-den');
                const nilaiInput = row.querySelector('.input-nilai');

                function calculatePercentage() {
                    const num = parseFloat(numInput.value);
                    const den = parseFloat(denInput.value);

                    if (!isNaN(num) && !isNaN(den) && den > 0) {
                        const pct = (num / den) * 100;
                        nilaiInput.value = Math.round(pct * 100) / 100;
                    }
                }

                if (numInput && denInput && nilaiInput) {
                    numInput.addEventListener('input', calculatePercentage);
                    denInput.addEventListener('input', calculatePercentage);
                }
            });
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeAddModal();
        });
    </script>
@endpush
