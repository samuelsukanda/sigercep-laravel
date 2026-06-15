@extends('layouts.app')

@section('title', 'SIGERCEP - Input Capaian Bulanan')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="font-bold text-2xl text-slate-800 dark:text-white">Input Capaian Bulanan</h3>
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
                    <h6 class="text-sm font-bold text-slate-800 dark:text-white uppercase m-0 ml-2">Pilih Periode & Kategori</h6>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                        // Set and round to 2 decimal places
                        nilaiInput.value = Math.round(pct * 100) / 100;
                    }
                }

                if (numInput && denInput && nilaiInput) {
                    numInput.addEventListener('input', calculatePercentage);
                    denInput.addEventListener('input', calculatePercentage);
                }
            });
        });
    </script>
@endpush
