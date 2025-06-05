<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIGERCEP</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://unpkg.com/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/nucleo/css/nucleo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Nucleo Icons -->
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/nucleo-svg.css') }}">
    <!-- Popper -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <!-- Main Styling -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard-tailwind.css') }}">
    {{-- Shortcut Icon --}}
    <link rel="shortcut icon" href="{{ asset('images/logors.png') }}" type="image/x-icon">
</head>

<body
    class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>
    <!-- sidenav  -->
    <aside
        class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 xl:ml-6 rounded-2xl xl:left-0 xl:translate-x-0"
        aria-expanded="false">
        <div class="h-19">
            <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden"
                sidenav-close></i>
            <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700"
                href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logors.png') }}"
                    class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8"
                    alt="main_logo" />
                <img src="{{ asset('images/logors.png') }}"
                    class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8"
                    alt="main_logo" />
                <span class="ml-1 font-3xl font-semibold transition-all duration-200 ease-nav-brand">SIGERCEP</span>
            </a>
        </div>

        <hr
            class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

        <div class="items-center block w-auto overflow-y-auto h-[calc(100vh-100px)] grow basis-full">

            <ul class="flex flex-col pl-0 mb-0">
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 bg-blue-500/13 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors"
                        href="{{ route('dashboard') }}">
                        <div
                            class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                            <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-wrench text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Komplain</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">IPSRS</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Outsourching & Vendor</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i
                                    class="relative top-0 text-sm leading-normal text-blue-500 ni ni-calendar-grid-58"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Reservasi</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Ruangan</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Kendaraan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-palette text-cyan-500 text-sm leading-normal relative top-0"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Desain Grafis</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Desain Grafis</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-radiation relative top-0 text-sm leading-normal text-green-800"
                                    style="color: #0da87c !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">K3RS</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Kecelakaan Kerja</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-users relative top-0 text-sm leading-normal"
                                    style="color: #9333ea !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Komite Mutu</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Mutu</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Bank SPO</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Manajemen Resiko</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-balance-scale relative top-0 text-sm leading-normal"
                                    style="color: #f22d2d !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">SDM & Hukum</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">UTW</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Struktur Organisasi</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Peraturan Perusahaan</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Surat Keputusan</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Buku Mandatory Training</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-warehouse relative top-0 text-sm leading-normal"
                                    style="color: #362ea8 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Pengadaan & Aset</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Peminjaman Aset</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Pengembalian Aset</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Pemindahan Aset</span>
                            </a>
                        </li>
                        <li class="w-full">
                            <a href="./pages/komplain/statistik.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-indigo-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Laporan Aset Rusak</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-notes-medical relative top-0 text-sm leading-normal"
                                    style="color: #df6969 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Kesiapan Ambulance</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Kesiapan Ambulance</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-print relative top-0 text-sm leading-normal"
                                    style="color: #5fd444 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Toner</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Toner</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-paper-plane relative top-0 text-sm leading-normal"
                                    style="color: #eec524 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Visitasi</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Visitasi</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-hand-holding relative top-0 text-sm leading-normal"
                                    style="color: #cb72f1 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Peminjaman</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Peminjaman</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="mt-0.5 w-full">
                    <!-- Trigger Komplain -->
                    <a href="javascript:;" onclick="toggleDropdown(this)"
                        class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                        <div class="flex items-center">
                            <div
                                class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-orange-500">
                                <i class="fas fa-server relative top-0 text-sm leading-normal"
                                    style="color: #47b1d1 !important;"></i>
                            </div>
                            <span class="ml-1 duration-300 ease">Hardware</span>
                        </div>
                        <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                        dropdown-menu style="max-height: 0; opacity: 0;" dropdown-menu>
                        <li class="w-full">
                            <a href="./pages/komplain/daftar.html"
                                class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                                <div
                                    class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center text-blue-400">
                                    <i class="fas fa-list text-sm leading-normal"></i>
                                </div>
                                <span class="ml-1">Hardware</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </aside>

    <!-- end sidenav -->

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
        <!-- Navbar -->
        <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start"
            navbar-main navbar-scroll="false">
            <div class="mt-4 flex items-center justify-between w-full px-4 flex-wrap-inherit">

                <div class="flex items-center space-x-4">
                    {{-- Hamburger --}}
                    <a href="javascript:;"
                        class="block p-0 text-sm text-white transition-all ease-nav-brand xl:hidden px-4"
                        sidenav-trigger>
                        <div class="w-4.5 overflow-hidden">
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                            <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-white transition-all"></i>
                            <i class="ease relative block h-0.5 rounded-sm bg-white transition-all"></i>
                        </div>
                    </a>

                    {{-- Page --}}
                    <h5 class="font-bold text-white capitalize mt-2">Dashboard</h5>
                </div>

                <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                    <div class="flex items-center md:ml-auto md:pr-4"></div>
                    <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                        {{-- Profile --}}
                        <li class="flex items-center h-full px-2">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-white font-semibold">{{ Auth::user()->name }}</span>
                            </div>
                        </li>

                        {{-- Settings --}}
                        <li class="flex items-center px-2">
                            <a href="javascript:;" class="p-0 text-sm text-white transition-all ease-nav-brand">
                                <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                            </a>
                        </li>

                        {{-- Logout --}}
                        <li class="relative flex items-center px-2">
                            <p class="hidden transform-dropdown-show"></p>

                            <!-- Tombol ikon dropdown -->
                            <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand"
                                dropdown-trigger aria-expanded="false">
                                <i class="cursor-pointer fa fa-power-off"></i>
                            </a>

                            <!-- Dropdown Menu -->
                            <ul dropdown-menu
                                class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white px-2 py-2 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:top-0 before:z-50 before:content-['\f0d8'] sm:-mr-6 lg:mt-2 lg:block">

                                <!-- Item Setting sebagai link -->
                                <li class="relative">
                                    <a href="./pages/setting.html"
                                        class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                        <i class="fas fa-cog mr-2"></i>
                                        Setting
                                    </a>
                                </li>

                                <li class="relative">
                                    <a href="./pages/setting.html"
                                        class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                        <i class="fas fa-right-from-bracket mr-2"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>

        </nav>

        <!-- end Navbar -->

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
                                            class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                            Today's Money</p>
                                        <h5 class="mb-2 font-bold dark:text-white">$53,000</h5>
                                        <p class="mb-0 dark:text-white dark:opacity-60">
                                            <span class="text-sm font-bold leading-normal text-emerald-500">+55%</span>
                                            since yesterday
                                        </p>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-blue-500 to-violet-500">
                                        <i
                                            class="ni leading-none ni-money-coins text-lg relative top-3.5 text-white"></i>
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
                                            class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                            Today's Users</p>
                                        <h5 class="mb-2 font-bold dark:text-white">2,300</h5>
                                        <p class="mb-0 dark:text-white dark:opacity-60">
                                            <span class="text-sm font-bold leading-normal text-emerald-500">+3%</span>
                                            since last week
                                        </p>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-red-600 to-orange-600">
                                        <i class="ni leading-none ni-world text-lg relative top-3.5 text-white"></i>
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
                                            class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                            New Clients</p>
                                        <h5 class="mb-2 font-bold dark:text-white">+3,462</h5>
                                        <p class="mb-0 dark:text-white dark:opacity-60">
                                            <span class="text-sm font-bold leading-normal text-red-600">-2%</span>
                                            since last quarter
                                        </p>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-emerald-500 to-teal-400">
                                        <i
                                            class="ni leading-none ni-paper-diploma text-lg relative top-3.5 text-white"></i>
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
                                            class="mb-0 font-sans text-sm font-semibold leading-normal uppercase dark:text-white dark:opacity-60">
                                            Sales</p>
                                        <h5 class="mb-2 font-bold dark:text-white">$103,430</h5>
                                        <p class="mb-0 dark:text-white dark:opacity-60">
                                            <span class="text-sm font-bold leading-normal text-emerald-500">+5%</span>
                                            than last month
                                        </p>
                                    </div>
                                </div>
                                <div class="px-3 text-right basis-1/3">
                                    <div
                                        class="inline-block w-12 h-12 text-center rounded-circle bg-gradient-to-tl from-orange-500 to-yellow-500">
                                        <i class="ni leading-none ni-cart text-lg relative top-3.5 text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- cards row 2 -->
            <div class="flex flex-wrap mt-6 -mx-3">
                <div class="w-full max-w-full px-3 mt-0 lg:w-7/12 lg:flex-none">
                    <div
                        class="border-black/12.5 dark:bg-slate-850 dark:shadow-dark-xl shadow-xl relative z-20 flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid p-6 pt-4 pb-0">
                            <h6 class="capitalize dark:text-white">Sales overview</h6>
                            <p class="mb-0 text-sm leading-normal dark:text-white dark:opacity-60">
                                <i class="fa fa-arrow-up text-emerald-500"></i>
                                <span class="font-semibold">4% more</span> in 2021
                            </p>
                        </div>
                        <div class="flex-auto p-4">
                            <div>
                                <canvas id="chart-line" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-full px-3 lg:w-5/12 lg:flex-none">
                    <div slider class="relative w-full h-full overflow-hidden rounded-2xl">
                        <!-- slide 1 -->
                        <div slide class="absolute w-full h-full transition-all duration-500">
                            <img class="object-cover h-full" src="./assets/img/carousel-1.jpg"
                                alt="carousel image" />
                            <div
                                class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
                                <div
                                    class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
                                    <i class="top-0.75 text-xxs relative text-slate-700 ni ni-camera-compact"></i>
                                </div>
                                <h5 class="mb-1 text-white">Get started with Argon</h5>
                                <p class="dark:opacity-80">There’s nothing I really wanted to do in life that I wasn’t
                                    able to get good at.</p>
                            </div>
                        </div>

                        <!-- slide 2 -->
                        <div slide class="absolute w-full h-full transition-all duration-500">
                            <img class="object-cover h-full" src="./assets/img/carousel-2.jpg"
                                alt="carousel image" />
                            <div
                                class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
                                <div
                                    class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
                                    <i class="top-0.75 text-xxs relative text-slate-700 ni ni-bulb-61"></i>
                                </div>
                                <h5 class="mb-1 text-white">Faster way to create web pages</h5>
                                <p class="dark:opacity-80">That’s my skill. I’m not really specifically talented at
                                    anything except for the ability to learn.</p>
                            </div>
                        </div>

                        <!-- slide 3 -->
                        <div slide class="absolute w-full h-full transition-all duration-500">
                            <img class="object-cover h-full" src="./assets/img/carousel-3.jpg"
                                alt="carousel image" />
                            <div
                                class="block text-start ml-12 left-0 bottom-0 absolute right-[15%] pt-5 pb-5 text-white">
                                <div
                                    class="inline-block w-8 h-8 mb-4 text-center text-black bg-white bg-center rounded-lg fill-current stroke-none">
                                    <i class="top-0.75 text-xxs relative text-slate-700 ni ni-trophy"></i>
                                </div>
                                <h5 class="mb-1 text-white">Share with us your design tips!</h5>
                                <p class="dark:opacity-80">Don’t be afraid to be wrong because you can’t learn anything
                                    from a compliment.</p>
                            </div>
                        </div>

                        <!-- Control buttons -->
                        <button btn-next
                            class="absolute z-10 w-10 h-10 p-2 text-lg text-white border-none opacity-50 cursor-pointer hover:opacity-100 far fa-chevron-right active:scale-110 top-6 right-4"></button>
                        <button btn-prev
                            class="absolute z-10 w-10 h-10 p-2 text-lg text-white border-none opacity-50 cursor-pointer hover:opacity-100 far fa-chevron-left active:scale-110 top-6 right-16"></button>
                    </div>
                </div>
            </div>

            <!-- cards row 3 -->

            <div class="flex flex-wrap mt-6 -mx-3">
                <div class="w-full max-w-full px-3 mt-0 mb-6 lg:mb-0 lg:w-7/12 lg:flex-none">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl dark:bg-gray-950 border-black-125 rounded-2xl bg-clip-border">
                        <div class="p-4 pb-0 mb-0 rounded-t-4">
                            <div class="flex justify-between">
                                <h6 class="mb-2 dark:text-white">Sales by Country</h6>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table
                                class="items-center w-full mb-4 align-top border-collapse border-gray-200 dark:border-white/40">
                                <tbody>
                                    <tr>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b w-3/10 whitespace-nowrap dark:border-white/40">
                                            <div class="flex items-center px-2 py-1">
                                                <div>
                                                    <img src="./assets/img/icons/flags/US.png" alt="Country flag" />
                                                </div>
                                                <div class="ml-6">
                                                    <p
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                        Country:</p>
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">United
                                                        States</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Sales:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">2500</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Value:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">$230,900</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 text-sm leading-normal align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="flex-1 text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Bounce:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">29.9%</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b w-3/10 whitespace-nowrap dark:border-white/40">
                                            <div class="flex items-center px-2 py-1">
                                                <div>
                                                    <img src="./assets/img/icons/flags/DE.png" alt="Country flag" />
                                                </div>
                                                <div class="ml-6">
                                                    <p
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                        Country:</p>
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">Germany
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Sales:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">3.900</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Value:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">$440,000</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 text-sm leading-normal align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="flex-1 text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Bounce:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">40.22%</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b w-3/10 whitespace-nowrap dark:border-white/40">
                                            <div class="flex items-center px-2 py-1">
                                                <div>
                                                    <img src="./assets/img/icons/flags/GB.png" alt="Country flag" />
                                                </div>
                                                <div class="ml-6">
                                                    <p
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                        Country:</p>
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">Great
                                                        Britain</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Sales:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">1.400</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Value:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">$190,700</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 text-sm leading-normal align-middle bg-transparent border-b whitespace-nowrap dark:border-white/40">
                                            <div class="flex-1 text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Bounce:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">23.44%</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-2 align-middle bg-transparent border-0 w-3/10 whitespace-nowrap">
                                            <div class="flex items-center px-2 py-1">
                                                <div>
                                                    <img src="./assets/img/icons/flags/BR.png" alt="Country flag" />
                                                </div>
                                                <div class="ml-6">
                                                    <p
                                                        class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                        Country:</p>
                                                    <h6 class="mb-0 text-sm leading-normal dark:text-white">Brasil</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-0 whitespace-nowrap">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Sales:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">562</h6>
                                            </div>
                                        </td>
                                        <td class="p-2 align-middle bg-transparent border-0 whitespace-nowrap">
                                            <div class="text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Value:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">$143,960</h6>
                                            </div>
                                        </td>
                                        <td
                                            class="p-2 text-sm leading-normal align-middle bg-transparent border-0 whitespace-nowrap">
                                            <div class="flex-1 text-center">
                                                <p
                                                    class="mb-0 text-xs font-semibold leading-tight dark:text-white dark:opacity-60">
                                                    Bounce:</p>
                                                <h6 class="mb-0 text-sm leading-normal dark:text-white">32.14%</h6>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-0 lg:w-5/12 lg:flex-none">
                    <div
                        class="border-black/12.5 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
                        <div class="p-4 pb-0 rounded-t-4">
                            <h6 class="mb-0 dark:text-white">Categories</h6>
                        </div>
                        <div class="flex-auto p-4">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li
                                    class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-t-lg rounded-xl text-inherit">
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                            <i class="text-white ni ni-mobile-button relative top-0.75 text-xxs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">
                                                Devices</h6>
                                            <span class="text-xs leading-tight dark:text-white/80">250 in stock, <span
                                                    class="font-semibold">346+ sold</span></span>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <button
                                            class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                                class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </li>
                                <li
                                    class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-xl text-inherit">
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                            <i class="text-white ni ni-tag relative top-0.75 text-xxs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">
                                                Tickets</h6>
                                            <span class="text-xs leading-tight dark:text-white/80">123 closed, <span
                                                    class="font-semibold">15 open</span></span>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <button
                                            class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                                class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </li>
                                <li
                                    class="relative flex justify-between py-2 pr-4 mb-2 border-0 rounded-b-lg rounded-xl text-inherit">
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                            <i class="text-white ni ni-box-2 relative top-0.75 text-xxs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">
                                                Error logs</h6>
                                            <span class="text-xs leading-tight dark:text-white/80">1 is active, <span
                                                    class="font-semibold">40 closed</span></span>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <button
                                            class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                                class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </li>
                                <li
                                    class="relative flex justify-between py-2 pr-4 border-0 rounded-b-lg rounded-xl text-inherit">
                                    <div class="flex items-center">
                                        <div
                                            class="inline-block w-8 h-8 mr-4 text-center text-black bg-center shadow-sm fill-current stroke-none bg-gradient-to-tl from-zinc-800 to-zinc-700 dark:bg-gradient-to-tl dark:from-slate-750 dark:to-gray-850 rounded-xl">
                                            <i class="text-white ni ni-satisfied relative top-0.75 text-xxs"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <h6 class="mb-1 text-sm leading-normal text-slate-700 dark:text-white">
                                                Happy users</h6>
                                            <span class="text-xs leading-tight dark:text-white/80"><span
                                                    class="font-semibold">+ 430 </span></span>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <button
                                            class="group ease-in leading-pro text-xs rounded-3.5xl p-1.2 h-6.5 w-6.5 mx-0 my-auto inline-block cursor-pointer border-0 bg-transparent text-center align-middle font-bold text-slate-700 shadow-none transition-all dark:text-white"><i
                                                class="ni ease-bounce text-2xs group-hover:translate-x-1.25 ni-bold-right transition-all duration-200"
                                                aria-hidden="true"></i></button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="pt-4">
                <div class="w-full px-6 mx-auto">
                    <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
                        <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                            <div class="text-sm leading-normal text-center text-slate-500 lg:text-left">
                                © 2025, Rumah Sakit Hamori
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end cards -->
    </main>
    <div fixed-plugin>
        <a fixed-plugin-button
            class="fixed px-4 py-2 text-xl bg-white shadow-lg cursor-pointer bottom-8 right-8 z-990 rounded-circle text-slate-700">
            <i class="py-2 pointer-events-none fa fa-cog"> </i>
        </a>
        <!-- -right-90 in loc de 0-->
        <div fixed-plugin-card
            class="z-sticky backdrop-blur-2xl backdrop-saturate-200 dark:bg-slate-850/80 shadow-3xl w-90 ease -right-90 fixed top-0 left-auto flex h-full min-w-0 flex-col break-words rounded-none border-0 bg-white/80 bg-clip-border px-2.5 duration-200">
            <div class="px-6 pt-4 pb-0 mb-0 border-b-0 rounded-t-2xl">
                <div class="float-left">
                    <h5 class="mt-4 mb-0 dark:text-white">SIGERCEP</h5>
                    <p class="dark:text-white dark:opacity-80">See our dashboard options.</p>
                </div>
                <div class="float-right mt-6">
                    <button fixed-plugin-close-button
                        class="inline-block p-0 mb-4 text-sm font-bold leading-normal text-center uppercase align-middle transition-all ease-in bg-transparent border-0 rounded-lg shadow-none cursor-pointer hover:-translate-y-px tracking-tight-rem bg-150 bg-x-25 active:opacity-85 dark:text-white text-slate-700">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr
                class="h-px mx-0 my-1 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />
            <div class="flex-auto p-6 pt-0 overflow-auto sm:pt-4">
                <!-- Sidenav Type -->
                <div class="mt-4">
                    <h6 class="mb-0 dark:text-white">Sidenav Type</h6>
                    <p class="text-sm leading-normal dark:text-white dark:opacity-80">Choose between 2 different
                        sidenav types.</p>
                </div>
                <div class="flex">
                    <button transparent-style-btn
                        class="inline-block w-full px-4 py-2.5 mb-2 font-bold leading-normal text-center text-white capitalize align-middle transition-all bg-blue-500 border border-transparent border-solid rounded-lg cursor-pointer text-sm xl-max:cursor-not-allowed xl-max:opacity-65 xl-max:pointer-events-none xl-max:bg-gradient-to-tl xl-max:from-blue-500 xl-max:to-violet-500 xl-max:text-white xl-max:border-0 hover:-translate-y-px dark:cursor-not-allowed dark:opacity-65 dark:pointer-events-none dark:bg-gradient-to-tl dark:from-blue-500 dark:to-violet-500 dark:text-white dark:border-0 hover:shadow-xs active:opacity-85 ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 bg-gradient-to-tl from-blue-500 to-violet-500 hover:border-blue-500"
                        data-class="bg-transparent" active-style>White</button>
                    <button white-style-btn
                        class="inline-block w-full px-4 py-2.5 mb-2 ml-2 font-bold leading-normal text-center text-blue-500 capitalize align-middle transition-all bg-transparent border border-blue-500 border-solid rounded-lg cursor-pointer text-sm xl-max:cursor-not-allowed xl-max:opacity-65 xl-max:pointer-events-none xl-max:bg-gradient-to-tl xl-max:from-blue-500 xl-max:to-violet-500 xl-max:text-white xl-max:border-0 hover:-translate-y-px dark:cursor-not-allowed dark:opacity-65 dark:pointer-events-none dark:bg-gradient-to-tl dark:from-blue-500 dark:to-violet-500 dark:text-white dark:border-0 hover:shadow-xs active:opacity-85 ease-in tracking-tight-rem shadow-md bg-150 bg-x-25 bg-none hover:border-blue-500"
                        data-class="bg-white">Dark</button>
                </div>
                <p class="block mt-2 text-sm leading-normal dark:text-white dark:opacity-80 xl:hidden">You can change
                    the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="flex my-4">
                    <h6 class="mb-0 dark:text-white">Navbar Fixed</h6>
                    <div class="block pl-0 ml-auto min-h-6">
                        <input navbarFixed
                            class="rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left mt-1 ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right"
                            type="checkbox" />
                    </div>
                </div>
                <hr
                    class="h-px my-6 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />
                <div class="flex mt-2 mb-12">
                    <h6 class="mb-0 dark:text-white">Light / Dark</h6>
                    <div class="block pl-0 ml-auto min-h-6">
                        <input dark-toggle
                            class="rounded-10 duration-250 ease-in-out after:rounded-circle after:shadow-2xl after:duration-250 checked:after:translate-x-5.3 h-5 relative float-left mt-1 ml-auto w-10 cursor-pointer appearance-none border border-solid border-gray-200 bg-slate-800/10 bg-none bg-contain bg-left bg-no-repeat align-top transition-all after:absolute after:top-px after:h-4 after:w-4 after:translate-x-px after:bg-white after:content-[''] checked:border-blue-500/95 checked:bg-blue-500/95 checked:bg-none checked:bg-right"
                            type="checkbox" />
                    </div>
                </div>
                <div class="w-full text-center">
                    <h6 class="mt-4 dark:text-white">&copy;
                        Rumah Sakit Hamori</h6>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- plugin for charts  -->
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<!-- plugin for scrollbar  -->
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<!-- main script file  -->
<script src="{{ asset('assets/js/argon-dashboard-tailwind.js') }}"></script>

</html>
