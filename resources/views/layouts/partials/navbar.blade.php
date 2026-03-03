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
                        @if (Auth::check())
                            <span class="text-sm font-semibold uppercase"
                                style="color: #7664E4 !important;">{{ ucwords(str_replace('.', ' ', Auth::user()->name)) }}</span>
                        @endif
                    </div>
                </li>
                <li class="flex items-center h-full px-1 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold" style="color: #7664E4 !important;"> - </span>
                    </div>
                </li>
                <li class="flex items-center h-full pr-2 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        @if (Auth::check())
                            <span class="text-sm font-semibold uppercase"
                                style="color: #7664E4 !important;">{{ Auth::user()->role }}</span>
                        @endif
                    </div>
                </li>

                <!-- Notifikasi Dropdown -->
                {{-- @auth
                    <div class="relative ml-3" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="relative p-1 text-gray-600 hover:text-gray-900 focus:outline-none">
                            <span class="sr-only">Notifikasi</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                            @if ($unreadCount > 0)
                                <span
                                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>

                        <!-- Dropdown Notifikasi -->
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg overflow-hidden z-20 border border-gray-200"
                            style="display: none;">
                            <div class="py-2">
                                <div class="px-4 py-2 text-sm font-semibold text-gray-700 border-b">Notifikasi</div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b last:border-0">
                                            <div class="flex justify-between items-start">
                                                <a href="{{ $notification->data['url'] ?? '#' }}?notif_id={{ $notification->id }}"
                                                    class="block px-4 py-3 hover:bg-gray-50 border-b last:border-0"
                                                    onclick="event.preventDefault(); markAndRedirect('{{ $notification->id }}', '{{ $notification->data['url'] ?? '#' }}');">
                                                    <div class="flex justify-between items-start">
                                                        <span
                                                            class="text-sm text-gray-800">{{ $notification->data['message'] }}</span>
                                                    </div>
                                                </a>
                                                <small
                                                    class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div class="mt-1">
                                                <a href="{{ route('notifications.read', $notification->id) }}"
                                                    class="text-xs text-blue-600 hover:underline">Tandai sudah dibaca</a>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi baru
                                        </div>
                                    @endforelse
                                </div>
                                @if (auth()->user()->unreadNotifications->count() > 0)
                                    <div class="px-4 py-2 border-t">
                                        <a href="{{ route('notifications.read-all') }}"
                                            class="text-xs text-blue-600 hover:underline">Tandai semua sudah dibaca</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endauth --}}
                {{-- Settings --}}
                {{-- <li class="flex items-center px-2">
                    <a href="javascript:;" class="p-0 text-sm transition-all ease-nav-brand"
                        style="color: #7664E4 !important;">
                        <i fixed-plugin-button-nav class="cursor-pointer fa fa-cog"></i>
                    </a>
                </li> --}}

                {{-- Logout --}}
                <li class="relative flex items-center px-2">
                    <p class="hidden transform-dropdown-show"></p>

                    <!-- Tombol ikon dropdown -->
                    <a href="javascript:;" dropdown-logout-trigger
                        class="block p-0 text-sm transition-all ease-nav-brand" aria-expanded="false"
                        style="color: #7664E4 !important;">
                        <i class="cursor-pointer fa fa-power-off"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul dropdown-logout-menu
                        class="text-sm transform-dropdown before:font-awesome before:leading-default before:duration-350 before:ease lg:shadow-3xl duration-250 min-w-44 before:sm:right-8 before:text-5.5 pointer-events-none absolute right-0 top-0 z-50 origin-top list-none rounded-lg border-0 border-transparent dark:shadow-dark-xl dark:bg-slate-850 bg-white px-2 py-2 text-left text-slate-500 opacity-0 transition-all before:absolute before:right-2 before:top-0 before:z-50 before:content-['\f0d8'] sm:-mr-6 lg:mt-2 lg:block">

                        <!-- Item Setting -->
                        {{-- <li class="relative">
                            <a href="{{ route('roles.index') }}"
                                class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                <i class="fas fa-cog mr-2"></i>
                                Settings
                            </a>
                        </li> --}}

                        <!-- Item Logout -->
                        <li class="relative">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                <i class="fas fa-right-from-bracket mr-2"></i>
                                Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>

        </div>
    </div>
</nav>
<!-- end Navbar -->
