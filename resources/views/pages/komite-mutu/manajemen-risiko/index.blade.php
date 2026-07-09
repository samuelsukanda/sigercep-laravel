@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Manajemen Risiko')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
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
            <h3 class="font-bold text-2xl text-slate-800 dark:text-white">Daftar Manajemen Risiko</h3>
        </div>

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        {{-- Statistics Row --}}
        <div class="flex flex-wrap -mx-3 mb-6">
            {{-- Card 1: Total Risiko --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Total Risiko</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $totalRisiko }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500 shadow-soft-2xl">
                                    <i class="fas fa-list text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Risiko Tinggi / Sangat Tinggi --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Risiko Tinggi & Sangat Tinggi</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $totalTinggiSangatTinggi }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600 shadow-soft-2xl">
                                    <i class="fas fa-exclamation-triangle text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 3: Jumlah Unit --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Jumlah Unit</p>
                                    <h4 class="mb-0 font-bold text-2xl dark:text-white">{{ $jumlahUnit }}</h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-emerald-500 to-teal-400 shadow-soft-2xl">
                                    <i class="fas fa-building text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 4: Filter Aktif --}}
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-xs font-semibold leading-normal uppercase text-slate-400 dark:text-white dark:opacity-60">
                                        Status Filter</p>
                                    <h4 class="mb-0 font-bold text-lg dark:text-white mt-1">
                                        @if ($isFiltered)
                                            <span class="text-blue-500">Aktif</span>
                                        @else
                                            <span class="text-slate-500">Semua Data</span>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-gray-400 to-gray-600 shadow-soft-2xl">
                                    <i class="fas fa-filter text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        @include('layouts.partials.komite-mutu.manajemen-risiko.filter')

        {{-- DataTable --}}
        @include('layouts.partials.komite-mutu.manajemen-risiko.datatable')

        {{-- Loading Overlay --}}
        @include('layouts.partials.komite-mutu.manajemen-risiko.loading-overlay')
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-manajemen-risiko.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
    <script>
        $.fn.dataTable.ext.errMode = "none";
    </script>
@endpush
