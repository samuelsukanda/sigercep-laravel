@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <!-- cards -->
    <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        <div class="flex flex-wrap -mx-3">
            <!-- card1 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Komplain
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">IPSRS</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalKomplainIpsrs }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeIpsrs }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-wrench text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Komplain
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Outsourcing Vendor</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalKomplainOutsourcingVendor }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeVendor }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600">
                                    <i class="fas fa-wrench text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card3 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Reservasi
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Ruangan</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalReservasiRuangan }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeRuangan }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-700 to-cyan-500 border-cyan-200">
                                    <i class="ni ni-calendar-grid-58 text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Reservasi
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Kendaraan</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalReservasiKendaraan }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeKendaraan }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-orange-500 to-yellow-500">
                                    <i class="ni ni-calendar-grid-58 text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 2 -->
        <div class="flex flex-wrap mt-6 -mx-3">
            
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('assets/js/carousel.js') }}"></script>
    @endpush
