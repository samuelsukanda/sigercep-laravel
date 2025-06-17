{{-- layouts/auth.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'SIGERCEP')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="{{ asset('images/logors.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center px-4">
    @yield('content')
</body>
</html>
