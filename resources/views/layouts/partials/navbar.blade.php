<!-- Navbar -->
<nav x-data="{ notifOpen: false, logoutOpen: false }"
    class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all ease-in shadow-none duration-250 rounded-2xl lg:flex-nowrap lg:justify-start"
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
                                style="color: #7664E4 !important;">{{ Auth::user()->jabatan }}</span>
                        @endif
                    </div>
                </li>

                <!-- Notifikasi -->
                @auth
                    <li class="relative flex items-center px-2">

                        <!-- BUTTON NOTIF -->

                        <button type="button" @click.stop="notifOpen = !notifOpen; logoutOpen = false"
                            class="block p-0 text-sm relative" style="color:#7664E4;">


                            <!-- ICON BELL -->
                            <i class="far fa-bell"></i>

                            @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp

                            <!-- 🔴 DOT -->
                            @if ($unreadCount > 0)
                                <span
                                    style="
                                        position: absolute;
                                        top: -7px;
                                        right: -11px;
                                        min-width: 14px;
                                        height: 14px;
                                        padding: 0 3px;
                                        background: red;
                                        color: white;
                                        font-size: 9px;
                                        font-weight: bold;
                                        border-radius: 999px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        line-height: 1;
                                    ">
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

                                <div style="max-height: 400px; overflow-y: auto;">

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
                                                            class="text-xs text-gray-500"
                                                            onmouseover="this.style.color='#2563eb'"
                                                            onmouseout="this.style.color='#6b7280'">
                                                            Tandai sudah dibaca
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    @empty
                                        <div class="px-3 py-2 text-sm text-gray-500 text-center">
                                            Tidak ada notifikasi
                                        </div>
                                    @endforelse

                                </div>

                                @if ($unreadCount > 0)
                                    <div class="px-2 py-1 border-t">
                                        <a href="{{ route('notifications.read-all') }}" class="text-xs text-gray-500"
                                            onmouseover="this.style.color='#2563eb'"
                                            onmouseout="this.style.color='#6b7280'">
                                            Tandai semua sudah dibaca
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </li>
                @endauth

                {{-- Permissions - Hanya untuk user tertentu --}}
                @php
                    $user = Auth::user();
                    $canAccessPermissions = false;

                    if ($user) {
                        $name = strtolower(trim($user->name ?? ''));
                        $unit = strtolower(trim($user->unit ?? ''));
                        $jabatan = strtolower(trim($user->jabatan ?? ''));

                        // Cek apakah user memenuhi kriteria
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
                        <a href="{{ route('permissions.index') }}" class="p-0 text-sm transition-all ease-nav-brand"
                            style="color: #7664E4 !important;">
                            <i class="cursor-pointer fa fa-cog"></i>
                        </a>
                    </li>
                @endif

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
                        style="color: #7664E4 !important;">
                        <i class="fa fa-power-off"></i>
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

@push('script')
    <script>
        function markAndRedirect(event, id, url) {
            event.preventDefault();

            fetch('/notifications/read/' + id, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(() => {
                    // hapus elemen notif dari DOM
                    event.target.closest('.border-b').remove();

                    window.location.href = url;
                });
            .catch(() => {
                window.location.href = url; // fallback tetap redirect
            });
        }

        function markOnly(id) {
            fetch('/notifications/read/' + id, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload(); // biar langsung hilang
            });
        }

        function markAllRead() {
            fetch('/notifications/read-all', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => {
                location.reload();
            });
        }
    </script>
@endpush
