<!-- Navbar -->
<nav x-data="{ notifOpen: false, logoutOpen: false, themeOpen: false }"
    class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start"
    navbar-main navbar-scroll="false">
    <div class="mt-4 flex items-center justify-between w-full px-4 flex-wrap-inherit">

        <div class="flex items-center space-x-4">
            {{-- Hamburger --}}
            <a href="javascript:;" class="block p-0 text-sm text-white transition-all ease-nav-brand xl:hidden px-4"
                sidenav-trigger>
                <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm transition-all"
                        style="background-color: var(--accent) !important;"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm transition-all"
                        style="background-color: var(--accent) !important;"></i>
                    <i class="ease relative block h-0.5 rounded-sm transition-all"
                        style="background-color: var(--accent) !important;"></i>
                </div>
            </a>

            {{-- Page --}}
            @php
                $navTitle = trim(strip_tags((string) $__env->yieldContent('title', 'SIGERCEP')));
                $navTitle = str_replace('SIGERCEP - ', '', $navTitle);
            @endphp
            <h5 class="font-bold capitalize mt-2" style="color: var(--accent) !important;">{{ $navTitle }}</h5>
        </div>

        <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4"></div>
            <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                {{-- Profile --}}
                <li class="flex items-center h-full cursor-pointer" title="Nama">
                    <div class="flex items-center space-x-3">
                        @if (Auth::check())
                            <span class="text-sm font-semibold uppercase"
                                style="color: var(--accent) !important;">{{ ucwords(str_replace('.', ' ', Auth::user()->name)) }}</span>
                        @endif
                    </div>
                </li>
                <li class="flex items-center h-full px-1 cursor-pointer">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold" style="color: var(--accent) !important;"> - </span>
                    </div>
                </li>
                <li class="flex items-center h-full pr-2 cursor-pointer" title="Jabatan">
                    <div class="flex items-center space-x-3">
                        @if (Auth::check())
                            <span class="text-sm font-semibold uppercase"
                                style="color: var(--accent) !important;">{{ Auth::user()->jabatan }}</span>
                        @endif
                    </div>
                </li>

                <!-- Notifikasi -->
                @auth
                    <li class="relative flex items-center px-2">

                        <!-- BUTTON NOTIF -->

                        <button type="button" @click.stop="notifOpen = !notifOpen; logoutOpen = false"
                            class="block p-0 text-sm relative" style="color:var(--accent);">


                            <!-- ICON BELL -->
                            <i class="far fa-bell" title="Notifikasi"></i>

                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp

                            <!-- 🔴 DOT -->
                            @if ($unreadCount > 0)
                                <span id="notif-badge" class="notif-badge">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif

                        </button>

                        <!-- DROPDOWN -->
                        <div x-show="notifOpen" @click.away="notifOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                            class="bg-white rounded-xl shadow-xl border border-gray-200 z-50"
                            style="display:none; position:fixed; top:70px; right:20px; width:360px;">

                            <div class="py-2">

                                <div class="px-5 py-3 text-sm font-semibold text-gray-700 border-b">
                                    Notifikasi
                                </div>

                                <div style="max-height: 400px; overflow-y: auto;" id="notif-list">

                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <div class="px-5 py-4 hover:bg-gray-50 border-b last:border-0">

                                            <div class="flex items-start">

                                                <!-- DOT ITEM -->
                                                <div
                                                    style="width:8px; height:8px; background:red; border-radius:50%; margin-top:6px; margin-right:10px;">
                                                </div>

                                                <div style="flex:1;">
                                                    <a href="{{ route('notifications.go', $notification->id) }}">

                                                        <div class="text-sm text-gray-800 font-medium">
                                                            {{ $notification->data['message'] }}
                                                        </div>

                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ $notification->created_at->diffForHumans() }}
                                                        </div>
                                                    </a>

                                                    <div class="mt-2">
                                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                                            class="text-xs notif-read-link text-gray-500">
                                                            Tandai sudah dibaca
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    @empty
                                        <div class="notif-empty px-3 py-2 text-sm text-gray-500 text-center">
                                            Tidak ada notifikasi
                                        </div>
                                    @endforelse

                                </div>

                                @if ($unreadCount > 0)
                                    <div class="px-2 py-1 border-t" id="notif-markall">
                                        <a href="{{ route('notifications.read-all') }}"
                                            class="text-xs notif-read-link text-gray-500">
                                            Tandai semua sudah dibaca
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </li>
                @endauth

                {{-- Approval Change Request --}}
                @php
                    $navUser = Auth::user();
                    $canAccessApprovalMapping = false;

                    if ($navUser) {
                        $nName = strtolower(trim($navUser->name ?? ''));
                        $nUnit = strtolower(trim($navUser->unit ?? ''));
                        $nJabatan = strtolower(trim($navUser->jabatan ?? ''));

                        if (
                            ($nName == 'sammuel' && $nUnit == 'teknologi dan informasi' && $nJabatan == 'operasional it technical support') ||
                            ($nName == 'deden eka nugraha' && $nUnit == 'teknologi dan informasi' && $nJabatan == 'spv it')
                        ) {
                            $canAccessApprovalMapping = true;
                        }
                    }
                @endphp

                @if ($canAccessApprovalMapping)
                    <li class="flex items-center px-2">
                        <a href="{{ route('approval-mapping.index') }}" title="Approval Change Request"
                            class="relative p-0 text-sm transition-all ease-nav-brand group"
                            style="color: var(--accent) !important;">
                            <i class="fas fa-user-check cursor-pointer"></i>

                            {{-- Tooltip --}}
                            <span
                                style="
                            display: none;
                            position: absolute;
                            top: calc(100% + 10px);
                            right: 50%;
                            transform: translateX(50%);
                            background: #1e1b4b;
                            color: #fff;
                            font-size: 11px;
                            font-weight: 600;
                            white-space: nowrap;
                            padding: 4px 10px;
                            border-radius: 6px;
                            pointer-events: none;
                            z-index: 9999;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                        "
                                class="navbar-tooltip">Approval Change Request</span>
                        </a>
                    </li>
                @endif

                {{-- Permissions --}}
                @php
                    $user = Auth::user();
                    $canAccessPermissions = false;

                    if ($user) {
                        $name = strtolower(trim($user->name ?? ''));
                        $unit = strtolower(trim($user->unit ?? ''));
                        $jabatan = strtolower(trim($user->jabatan ?? ''));

                        if (
                            $name == 'sammuel' &&
                            $unit == 'teknologi dan informasi' &&
                            $jabatan == 'operasional it technical support'
                        ) {
                            $canAccessPermissions = true;
                        }
                    }
                @endphp

                @if ($canAccessPermissions)
                    <li class="flex items-center px-2">
                        <a href="{{ route('permissions.index') }}" title="Permission"
                            class="p-0 text-sm transition-all ease-nav-brand" style="color: var(--accent) !important;">
                            <i class="cursor-pointer fa fa-cog"></i>
                        </a>
                    </li>

                    <li class="flex items-center px-2">
                        <a href="{{ route('user.monitoring') }}" title="Monitoring User"
                            class="p-0 text-sm transition-all ease-nav-brand" style="color: var(--accent) !important;">
                            <i class="cursor-pointer fa fa-signal"></i>
                        </a>
                    </li>

                    <li class="flex items-center px-2">
                        <a href="{{ route('users') }}" title="Daftar User"
                            class="p-0 text-sm transition-all ease-nav-brand" style="color: var(--accent) !important;">
                            <i class="cursor-pointer fa fa-users"></i>
                        </a>
                    </li>
                @endif

                {{-- Tema: warna --}}
                <li class="relative flex items-center px-2">
                    <button type="button" @click.stop="themeOpen = !themeOpen; notifOpen = false; logoutOpen = false"
                        class="block p-0 text-sm relative" style="color:var(--accent);" title="Pilih warna tema">
                        <i class="fas fa-palette"></i>
                    </button>

                    <div x-show="themeOpen" @click.away="themeOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="bg-white rounded-xl shadow-xl border border-gray-200 z-50 dark:bg-slate-850 dark:border-slate-700"
                        style="display:none; position:fixed; top:70px; right:20px; width:220px;">
                        <div
                            class="px-4 py-3 text-sm font-semibold text-gray-700 border-b dark:text-white dark:border-slate-700">
                            Warna Tema
                        </div>
                        <div class="px-4 py-3">
                            <div class="theme-label">Pilih warna tema</div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                <button type="button" class="theme-swatch" style="background:#7664E4"
                                    data-theme="violet" title="Ungu"
                                    onclick="SIGERCEP.setTheme('violet')"></button>
                                <button type="button" class="theme-swatch" style="background:#2f6690"
                                    data-theme="blue" title="Biru Tua" onclick="SIGERCEP.setTheme('blue')"></button>
                                <button type="button" class="theme-swatch" style="background:#84a98c"
                                    data-theme="green" title="Hijau Sage"
                                    onclick="SIGERCEP.setTheme('green')"></button>
                                <button type="button" class="theme-swatch" style="background:#f48c06"
                                    data-theme="orange" title="Oranye"
                                    onclick="SIGERCEP.setTheme('orange')"></button>
                                <button type="button" class="theme-swatch" style="background:#fcbf49"
                                    data-theme="red" title="Amber" onclick="SIGERCEP.setTheme('red')"></button>
                                <button type="button" class="theme-swatch" style="background:#ffb3c6"
                                    data-theme="cyan" title="Merah Muda"
                                    onclick="SIGERCEP.setTheme('cyan')"></button>
                                <button type="button" class="theme-swatch" style="background:#17c3b2"
                                    data-theme="teal" title="Teal" onclick="SIGERCEP.setTheme('teal')"></button>
                                <button type="button" class="theme-swatch" style="background:#bc4749"
                                    data-theme="rust" title="Merah Tanah"
                                    onclick="SIGERCEP.setTheme('rust')"></button>
                                <button type="button" class="theme-swatch" style="background:#99582a"
                                    data-theme="cream" title="Coklat" onclick="SIGERCEP.setTheme('cream')"></button>
                                <button type="button" class="theme-swatch" style="background:#81c3d7"
                                    data-theme="sky" title="Biru Muda" onclick="SIGERCEP.setTheme('sky')"></button>
                                <button type="button" class="theme-swatch" style="background:#f07167"
                                    data-theme="coral" title="Koral" onclick="SIGERCEP.setTheme('coral')"></button>
                                <button type="button" class="theme-swatch" style="background:#adc178"
                                    data-theme="sage" title="Sage" onclick="SIGERCEP.setTheme('sage')"></button>
                            </div>
                        </div>
                    </div>
                </li>

                {{-- Toggle dark mode --}}
                <li class="relative flex items-center px-2">
                    <button type="button" onclick="SIGERCEP.toggleDark()" class="dark-toggle"
                        title="Mode gelap / terang" aria-label="Mode gelap / terang">
                        <i class="fas fa-sun dark-toggle-sun"></i>
                        <span class="dark-toggle-knob"></span>
                        <i class="fas fa-moon dark-toggle-moon"></i>
                    </button>
                </li>

                {{-- Logout --}}
                <li class="relative flex items-center px-2">
                    <p class="hidden transform-dropdown-show"></p>

                    <!-- Tombol ikon dropdown -->
                    <a href="javascript:;" @click="logoutOpen = !logoutOpen; notifOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                        class="block p-0 text-sm transition-all ease-nav-brand" aria-expanded="false"
                        style="color: var(--accent) !important;">
                        <i class="fa fa-power-off" title="Logout"></i>
                    </a>

                    <!-- Dropdown Menu -->
                    <ul x-show="logoutOpen" @click.away="logoutOpen = false" x-transition
                        class="bg-white rounded-lg shadow-lg z-50"
                        style="
                            display:none;
                            position: fixed;
                            top: 70px;
                            right: 20px;
                            min-width: 160px;
                        ">

                        <!-- Item Setting -->
                        <li class="relative">
                            <a href="{{ route('profile') }}"
                                class="dark:hover:bg-slate-900 ease py-1.2 clear-both block w-full whitespace-nowrap rounded-lg px-4 duration-300 hover:bg-gray-200 hover:text-slate-700 lg:transition-colors text-sm font-semibold dark:text-white">
                                <i class="fas fa-user mr-2"></i>
                                Profil
                            </a>
                        </li>

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

