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
                                        Komplain
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Kesehatan Lingkungan</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalKesehatanLingkungan }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeKesehatanLingkungan }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-700 to-cyan-500 border-cyan-200">
                                    <i class="fas fa-wrench text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 2 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fa-solid fa-calendar-days text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
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
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600">
                                    <i class="fa-solid fa-calendar-days text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 3 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Desain Grafis
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Desain Grafis</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalDesainGrafis }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeDesain }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-palette text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- cards row 4 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        K3RS
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Kecelakaan Kerja</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalK3RS }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-semibold leading-normal text-emerald-500">
                                            {{ $lastInputTimeK3RS }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-radiation text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 5 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Komite Mutu
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Mutu</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalMutu }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeMutu }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-users text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Komite Mutu
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Bank SPO</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalBankSpo }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeBankSpo }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600">
                                    <i class="fas fa-users text-lg text-white"></i>
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
                                        Komite Mutu
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Manajemen Risiko</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalManajemenRisiko }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeManajemenRisiko }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-700 to-cyan-500 border-cyan-200">
                                    <i class="fas fa-users text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Komite Mutu
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Pelaporan IKP</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPelaporanIkp }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePelaporanIkp }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-orange-500 to-yellow-500">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 6 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Komite Mutu
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Pengajuan Dokumen</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPengajuanDokumen }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePengajuanDokumen }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 7 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        SDM & Hukum
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">UTW</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalUtw }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeUtw }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        SDM & Hukum
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Peraturan Perusahaan</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPeraturanPerusahaan }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePeraturanPerusahaan }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
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
                                        SDM & Hukum
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Surat Keputusan</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalSuratKeputusan }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeSuratKeputusan }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-700 to-cyan-500 border-cyan-200">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        SDM & Hukum
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Mandatory Training</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalMandatoryTraining }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeMandatoryTraining }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-orange-500 to-yellow-500">
                                    <i class="fas fa-balance-scale text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 8 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Pengadaan Aset
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Peminjaman Aset</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPeminjamanAset }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePeminjamanAset }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-warehouse text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card2 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Pengadaan Aset
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Pengembalian Aset</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPengembalianAset }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePengembalianAset }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-red-600 to-orange-600">
                                    <i class="fas fa-warehouse text-lg text-white"></i>
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
                                        Pengadaan Aset
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Pemindahan Aset</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPemindahanAset }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePemindahanAset }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-700 to-cyan-500 border-cyan-200">
                                    <i class="fas fa-warehouse text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- card4 -->
            <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-0 xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Pengadaan Aset
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Laporan Aset Rusak</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalLaporanAsetRusak }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeLaporanAsetRusak }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-orange-500 to-yellow-500">
                                    <i class="fas fa-warehouse text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 9 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Komite Medik
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Medik</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalKomiteMedik }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeKomiteMedik }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-laptop-medical text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 10 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Ambulance
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Kesiapan Ambulance</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalAmbulance }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeAmbulance }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-notes-medical text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 11 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Toner
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Toner</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalToner }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeToner }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-print text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 12 -->
        <div class="flex flex-wrap mt-6 -mx-3">
            <!-- card1 -->
            <div class="w-full max-w-full px-3 sm:w-1/2 sm:flex-none xl:w-1/4">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="flex-auto p-4">
                        <div class="flex flex-row -mx-3">
                            <div class="flex-none w-2/3 max-w-full px-3">
                                <div>
                                    <p
                                        class="mb-0 font-sans text-md font-bold leading-normal uppercase dark:text-white dark:opacity-60">
                                        Visitasi
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Visitasi</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalVisitasi }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimeVisitasi }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-paper-plane text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 13 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Peminjaman
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Peminjaman Barang</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalPeminjaman }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span
                                            class="text-sm font-semibold leading-normal text-emerald-500">{{ $lastInputTimePeminjaman }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-hand-holding text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- cards row 14 -->
        <div class="flex flex-wrap mt-6 -mx-3">
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
                                        Hardware
                                    </p>
                                    <span class="mb-0 font-semibold text-sm dark:text-white">Ceklis Hardware</span>
                                    <h5 class="mb-0 font-bold text-sm dark:text-white">
                                        Total Data: {{ $totalHardware }}
                                    </h5>
                                    <p class="mb-0 dark:text-white dark:opacity-60">
                                        <span class="text-sm font-semibold leading-normal text-emerald-500">
                                            {{ $lastInputTimeHardware }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="px-3 text-right basis-1/3">
                                <div
                                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tl from-blue-500 to-violet-500">
                                    <i class="fas fa-server text-lg text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
