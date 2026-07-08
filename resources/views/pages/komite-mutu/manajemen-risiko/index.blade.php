@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Risiko')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/filter-responsive.css') }}">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-bold text-2xl text-slate-800 dark:text-white">Daftar Risiko RS Hamori 2026</h3>
        </div>

        @if (session('success'))
            <div class="relative text-sm w-full p-4 mb-4 text-white border border-transparent rounded-lg bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-md">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Statistics Row --}}
        <div class="flex flex-wrap -mx-3 mb-6">
            {{-- Card 1: Total Risiko --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">Total Risiko</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $totalRisiko }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500 shadow-soft-2xl">
                                    <i class="fas fa-list text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Risiko Tinggi / Sangat Tinggi --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">Risiko Tinggi & Sangat Tinggi</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $totalTinggiSangatTinggi }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600 shadow-soft-2xl">
                                    <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Jumlah Unit --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">Jumlah Unit</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $jumlahUnit }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-soft-2xl">
                                    <i class="fas fa-building text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Filter Aktif --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">Status Filter</p>
                                    <h4 class="mb-0 font-bold text-lg dark:text-white mt-1">
                                        @if($isFiltered)
                                            <span class="text-blue-500">Aktif</span>
                                        @else
                                            <span class="text-slate-500">Semua Data</span>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-gray-400 to-gray-600 shadow-soft-2xl">
                                    <i class="fas fa-filter text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section (Reservasi Ruangan Style) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="px-5 py-4">
                <form method="GET" action="{{ route('komite-mutu.manajemen-risiko.index') }}" id="filterForm">
                    <div class="flex flex-wrap gap-3 items-end filter-wrap">
                        
                        {{-- Filter Unit --}}
                        <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                            <label class="text-xs font-semibold text-gray-600 mb-1.5">Unit</label>
                            <select name="unit" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Semua Unit --</option>
                                @foreach ($unitOptions as $opt)
                                    <option value="{{ $opt }}" {{ $unitFilter === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Tingkat Risiko --}}
                        <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                            <label class="text-xs font-semibold text-gray-600 mb-1.5">Tingkat Risiko</label>
                            <select name="tingkat" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Semua Tingkat --</option>
                                @foreach ($tingkatOptions as $opt)
                                    <option value="{{ $opt }}" {{ $tingkatFilter === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Kode Risiko --}}
                        <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                            <label class="text-xs font-semibold text-gray-600 mb-1.5">Kode Risiko</label>
                            <select name="kode_risiko" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Semua Kode --</option>
                                @foreach ($kodeOptions as $opt)
                                    <option value="{{ $opt }}" {{ $kodeFilter === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-end flex-1 justify-between filter-action">
                            <div class="flex items-end">
                                <!-- Button Cari -->
                                <button type="submit"
                                    class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                                    style="background-color: #7664E4 !important;">
                                    <i class="fas fa-search text-sm leading-normal"></i>
                                </button>

                                <!-- Button Reset -->
                                <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
                                    class="btn-reset inline-flex items-center justify-center
                                        h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                                        rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                                    Reset
                                </a>
                            </div>

                            {{-- Tambah Data --}}
                            @canAccess('manajemen_risiko', 'create')
                            <a href="{{ route('komite-mutu.manajemen-risiko.create') }}"
                                class="inline-flex items-center justify-center
                                    h-9 px-4 text-xs font-semibold text-white uppercase
                                    rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                                style="background-color: #7664E4 !important;">
                                Tambah Data
                            </a>
                            @endcanAccess
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="bg-white dark:bg-slate-850 rounded-2xl shadow-xl border border-gray-200 dark:border-slate-800 overflow-hidden mb-8">
            <div class="overflow-x-auto custom-scrollbar p-6">
                <table class="w-full text-left border-collapse border border-gray-300 dark:border-slate-700">
                    <thead>
                        <tr class="text-slate-400 dark:text-slate-300 text-xs font-bold uppercase bg-slate-50 dark:bg-slate-900/30">
                            <th class="py-3 px-3 text-center border border-gray-300 dark:border-slate-700">No Urut</th>
                            <th class="py-3 px-3 text-center border border-gray-300 dark:border-slate-700">Unit</th>
                            <th class="py-3 px-4 min-w-[300px] border border-gray-300 dark:border-slate-700">Risiko</th>
                            <th class="py-3 px-4 min-w-[150px] border border-gray-300 dark:border-slate-700">Kode Risiko</th>
                            <th class="py-3 px-4 min-w-[300px] border border-gray-300 dark:border-slate-700">Sebab</th>
                            <th class="py-3 px-4 min-w-[200px] border border-gray-300 dark:border-slate-700">Dampak</th>
                            <th class="py-3 px-4 min-w-[200px] border border-gray-300 dark:border-slate-700">Pengendalian</th>
                            <th class="py-3 px-3 text-center min-w-[100px] border border-gray-300 dark:border-slate-700">Tingkat<br>Analisis</th>
                            <th class="py-3 px-4 min-w-[150px] border border-gray-300 dark:border-slate-700">Target Waktu</th>
                            <th class="py-3 px-3 text-center min-w-[100px] border border-gray-300 dark:border-slate-700">Tingkat<br>Mitigasi</th>
                            <th class="py-3 px-3 text-center w-28 border border-gray-300 dark:border-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                        @php
                            if (!function_exists('getBadgeColor')) {
                                function getBadgeColor($tingkat) {
                                    if (empty($tingkat)) return 'bg-slate-100 text-slate-500';
                                    $t = strtolower(trim($tingkat));
                                    if (str_contains($t, 'sangat rendah')) return 'bg-green-100 text-green-700';
                                    if (str_contains($t, 'rendah')) return 'bg-yellow-100 text-yellow-700';
                                    if (str_contains($t, 'sangat tinggi')) return 'bg-red-200 text-red-800 font-bold';
                                    if (str_contains($t, 'tinggi')) return 'bg-red-100 text-red-700';
                                    if (str_contains($t, 'sedang')) return 'bg-orange-100 text-orange-700';
                                    return 'bg-slate-100 text-slate-700';
                                }
                            }
                        @endphp
                        @forelse ($risikos as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="py-4 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <span class="block text-slate-700 dark:text-slate-300 text-xs">{{ $item->no_urut }}</span>
                                </td>
                                
                                <td class="py-4 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <span class="block font-bold text-slate-700 dark:text-slate-300 text-xs">{{ $item->unit }}</span>
                                </td>
                                
                                <td class="py-4 px-4 font-semibold text-slate-800 dark:text-slate-200 border border-gray-300 dark:border-slate-700">
                                    {{ $item->risiko }}
                                </td>

                                <td class="py-4 px-4 text-xs font-medium text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700">
                                    {{ $item->kode_risiko }}
                                </td>

                                <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700">
                                    {{ $item->sebab }}
                                </td>

                                <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700">
                                    {{ $item->dampak }}
                                </td>

                                <td class="py-4 px-4 text-xs text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700">
                                    {{ $item->pengendalian }}
                                    @if($item->efektif)
                                        <span class="block mt-1 text-emerald-600 font-semibold"><i class="fas fa-check mr-1"></i> Efektif</span>
                                    @endif
                                    @if($item->tidak_efektif)
                                        <span class="block mt-1 text-red-500 font-semibold"><i class="fas fa-times mr-1"></i> Tidak Efektif</span>
                                    @endif
                                </td>

                                <td class="py-4 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ getBadgeColor($item->analisis_tingkat) }}">
                                        {{ $item->analisis_tingkat ?? '-' }}
                                    </span>
                                    @if($item->analisis_nilai)
                                        <span class="block text-[10px] mt-1 text-slate-400">Skor: {{ (float)$item->analisis_nilai }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-xs font-medium text-slate-500 dark:text-slate-400 border border-gray-300 dark:border-slate-700 text-center">
                                    {{ $item->target_waktu ?? '-' }}
                                </td>

                                <td class="py-4 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ getBadgeColor($item->mitigasi_tingkat) }}">
                                        {{ $item->mitigasi_tingkat ?? '-' }}
                                    </span>
                                    @if($item->mitigasi_nilai)
                                        <span class="block text-[10px] mt-1 text-slate-400">Skor: {{ (float)$item->mitigasi_nilai }}</span>
                                    @endif
                                </td>

                                <td class="py-3 px-3 text-center border border-gray-300 dark:border-slate-700">
                                    <div class="flex items-center justify-center gap-2">
                                        @canAccess('manajemen_risiko', 'read')
                                        <a href="{{ route('komite-mutu.manajemen-risiko.show', $item->id) }}"
                                            class="text-emerald-500 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 p-1.5 rounded transition-colors"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endcanAccess
                                        
                                        @canAccess('manajemen_risiko', 'update')
                                        <a href="{{ route('komite-mutu.manajemen-risiko.edit', $item->id) }}"
                                            class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded transition-colors"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcanAccess

                                        @canAccess('manajemen_risiko', 'delete')
                                        <button type="button" onclick="confirmDelete({{ $item->id }})"
                                            class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-1.5 rounded transition-colors"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        @endcanAccess
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-12 text-center text-slate-500 border border-gray-300 dark:border-slate-700">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <i class="fas fa-folder-open text-4xl text-slate-300"></i>
                                        <span class="font-bold text-slate-400">Tidak ada data daftar risiko ditemukan.</span>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    let url = "{{ route('komite-mutu.manajemen-risiko.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>
@endpush
