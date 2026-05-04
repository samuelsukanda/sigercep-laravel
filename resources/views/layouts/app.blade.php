{{-- layouts/app.blade.php --}}
@include('layouts.partials.header')
@stack('styles')

<meta name="csrf-token" content="{{ csrf_token() }}">

<body data-page="virtual-reality"
    class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900
            leading-default bg-gray-50 text-slate-500
            min-h-screen flex flex-col">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    <main class="relative flex-1 transition-all duration-200 ease-in-out xl:ml-68 rounded-xl flex flex-col">
        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Konten halaman --}}
        <div class="flex-1">
            @yield('content')
        </div>

        {{-- Footer --}}
        @includeIf('layouts.partials.footer')

        {{-- Fixed Plugin --}}
        {{-- @include('layouts.partials.fixed') --}}
    </main>

</body>

{{-- Scripts --}}
@include('layouts.partials.scripts')
@stack('scripts')
</html>
