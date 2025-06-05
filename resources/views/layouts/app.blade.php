{{-- layouts/app.blade.php --}}
@include('layouts.partials.header')

<body
    class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute w-full bg-blue-500 dark:hidden min-h-75"></div>

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Konten halaman --}}
        @yield('content')

        {{-- Footer --}}
        @includeIf('layouts.partials.footer')
    </main>

    {{-- Fixed Plugin --}}
    @include('layouts.partials.fixed')
</body>

<!-- plugin for charts  -->
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
<!-- plugin for scrollbar  -->
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<!-- main script file  -->
<script src="{{ asset('assets/js/argon-dashboard-tailwind.js') }}"></script>
{{-- end Footer --}}

</html>