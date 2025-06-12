{{-- layouts/app.blade.php --}}
@include('layouts.partials.header')

<body data-page="virtual-reality"
    class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">

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

</body>

{{-- Scripts --}}
@include('layouts.partials.scripts')
@stack('scripts')

</html>