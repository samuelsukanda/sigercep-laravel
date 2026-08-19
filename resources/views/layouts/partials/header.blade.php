{{-- Header --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <script>
        (function () {
            try {
                var dark = localStorage.getItem('sigercep-dark');
                var theme = localStorage.getItem('sigercep-theme');
                var root = document.documentElement;
                if (theme) root.setAttribute('data-theme', theme);
                if (dark === 'on' || (dark === null && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    root.classList.add('dark');
                }
            } catch (e) { }
        })();
    </script>
    <title>@yield('title', 'SIGERCEP')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Informasi dan Gangguan Rumah Sakit Rujukan (SIGERCEP) - pengelolaan helpdesk, komplain, aset, dan dokumen." />
    <meta property="og:title" content="SIGERCEP" />
    <meta property="og:description" content="Sistem Informasi dan Gangguan Rumah Sakit Rujukan" />
    <meta property="og:type" content="website" />
    <!-- Fonts and icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Main Styling -->
    <link rel="stylesheet" href="{{ asset('assets/css/argon-dashboard-tailwind.css') }}">
    {{-- Shortcut Icon --}}
    <link rel="shortcut icon" href="{{ asset('images/logors.png') }}" type="image/x-icon">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    {{-- Responsive Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <!-- Laravel Toaster -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    {{-- Theme CSS (redesign overrides) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    {{-- Dark theme overrides --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}">
</head>
{{-- end Header --}}
