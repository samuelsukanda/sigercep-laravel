<!-- sidebar  -->
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
                        <i class="fa-solid fa-tv text-sm leading-normal text-blue-500"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
                </a>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-wrench text-sm leading-normal text-orange-500"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Komplain</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('komplain.ipsrs.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">IPSRS</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('komplain.outsourcing-vendor.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Outsourching & Vendor</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('komplain.kesehatan-lingkungan.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Kesehatan Lingkungan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fa-solid fa-calendar-days text-sm leading-normal text-blue-500"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Reservasi</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('reservasi.ruangan.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Ruangan</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('reservasi.kendaraan.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Kendaraan</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-palette text-cyan-500 text-sm leading-normal relative top-0"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Desain Grafis</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('desain-grafis.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Desain Grafis</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-radiation text-red-500 text-sm leading-normal relative top-0"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">K3RS</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('kecelakaan-kerja.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Kecelakaan Kerja</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-users text-blue-500 relative top-0 text-sm leading-normal"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Komite Mutu</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('komite-mutu.mutu.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Mutu</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="./pages/komplain/statistik.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Bank SPO</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Manajemen Risiko</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-balance-scale relative top-0 text-sm leading-normal"
                                style="color: #ee8a0f !important;"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">SDM & Hukum</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="./pages/komplain/daftar.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">UTW</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('sdm-hukum.struktur-organisasi.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Struktur Organisasi</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="./pages/komplain/statistik.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Peraturan Perusahaan</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="./pages/komplain/statistik.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Surat Keputusan</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="./pages/komplain/statistik.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Mandatory Training</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-warehouse relative top-0 text-sm leading-normal"
                                style="color: #362ea8 !important;"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Pengadaan & Aset</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('pengadaan-aset.peminjaman-aset.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Peminjaman Aset</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('pengadaan-aset.pengembalian-aset.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Pengembalian Aset</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('pengadaan-aset.pemindahan-aset.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Pemindahan Aset</span>
                        </a>
                    </li>
                    <li class="w-full">
                        <a href="{{ route('pengadaan-aset.laporan-aset-rusak.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Laporan Aset Rusak</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-notes-medical relative top-0 text-sm leading-normal text-orange-500"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Kesiapan Ambulance</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('kesiapan-ambulance.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Kesiapan Ambulance</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-print relative top-0 text-sm leading-normal text-blue-500"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Toner</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="#"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Toner</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-paper-plane relative top-0 text-sm leading-normal"
                                style="color: #eec524 !important;"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Visitasi</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('visitasi.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Visitasi</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-hand-holding relative top-0 text-sm leading-normal"
                                style="color: #cb72f1 !important;"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Peminjaman</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="{{ route('peminjaman.index') }}"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                                <i class="fas fa-list text-sm leading-normal"></i>
                            </div>
                            <span class="ml-1">Peminjaman</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-0.5 w-full">
                <!-- Trigger -->
                <a href="javascript:;" onclick="toggleDropdown(this)"
                    class="dark:text-white dark:opacity-80 py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center justify-between whitespace-nowrap rounded-lg px-4 font-semibold text-slate-700 transition-colors hover:bg-blue-50">
                    <div class="flex items-center">
                        <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
                            <i class="fas fa-server relative top-0 text-sm leading-normal"
                                style="color: #47b1d1 !important;"></i>
                        </div>
                        <span class="ml-1 duration-300 ease">Hardware</span>
                    </div>
                    <i class="fas fa-chevron-down text-sm transition-transform duration-200"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="max-h-0 overflow-hidden flex-col pl-10 mt-1 space-y-1 transition-all duration-300 ease-in-out"
                    style="max-height: 0; opacity: 0;" dropdown-menu>
                    <li class="w-full">
                        <a href="./pages/komplain/daftar.html"
                            class="py-2.7 text-sm ease-nav-brand mx-2 flex items-center whitespace-nowrap rounded-lg px-4 font-normal text-slate-600 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80">
                            <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center">
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
<!-- end Sidebar -->
