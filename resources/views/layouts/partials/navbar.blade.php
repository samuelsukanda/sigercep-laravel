<!-- Navbar -->
<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start"
    navbar-main navbar-scroll="false">
    <div class="mt-4 flex items-center justify-between w-full px-4 flex-wrap-inherit">

        <div class="flex items-center space-x-4">
            {{-- Hamburger --}}
            <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand xl:hidden px-4"
                sidenav-trigger>
                <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm transition-all"
                        style="background-color: #7664E4 !important;"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm transition-all"
                        style="background-color: #7664E4 !important;"></i>
                    <i class="ease relative block h-0.5 rounded-sm transition-all"
                        style="background-color: #7664E4 !important;"></i>
                </div>
            </a>

            {{-- Page --}}
            <h5 class="font-bold capitalize mt-2" style="color: #7664E4 !important;">Dashboard</h5>
        </div>

        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4"></div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                {{-- Profile --}}
                <li class="flex items-center h-full cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold uppercase"
                            style="color: #7664E4 !important;">{{ Auth::user()->name }}</span>
                    </div>
                </li>
                <li class="flex items-center h-full px-1 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold" style="color: #7664E4 !important;"> - </span>
                    </div>
                </li>
                <li class="flex items-center h-full pr-2 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold uppercase"
                            style="color: #7664E4 !important;">{{ Auth::user()->level }}</span>
                    </div>
                </li>

                {{-- Settings --}}
                <li class="flex items-center px-2">
                    <a href="javascript:;" class="p-0 text-sm transition-all ease-nav-brand"
                        style="color: #7664E4 !important;">
                        <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="relative flex items-center px-2">
                    <p class="hidden transform-dropdown-show"></p>

                    <!-- Tombol ikon dropdown -->
                    <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand" dropdown-trigger
                        aria-expanded="false" style="color: #7664E4 !important;">
                        <i class="cursor-pointer fa fa-power-off"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul dropdown-menu
                        class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white px-2 py-2 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:top-0 before:z-50 before:content-['\f0d8'] sm:-mr-6 lg:mt-2 lg:block">

                        <!-- Item Setting sebagai link -->
                        <li class="relative">
                            <a href="#"
                                class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                <i class="fas fa-cog mr-2"></i>
                                Settings
                            </a>
                        </li>

                        <li class="relative">
                            <a href="{{ route('logout') }}"
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
