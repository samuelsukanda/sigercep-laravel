@extends('layouts.auth')

@section('title', 'Login - SIGERCEP')

@section('content')
<div class="w-full max-w-6xl bg-gray-800 rounded-xl shadow-lg grid grid-cols-1 md:grid-cols-2 overflow-hidden">
    {{-- LEFT SECTION --}}
    <div class="p-10">
        <h2 class="text-3xl font-bold mb-2">Log In</h2>
        <p class="text-gray-400 mb-6">Enter your username and password</p>

        {{-- Session & Validation --}}
        @if (session('error'))
            <div class="bg-red-500 text-white text-sm p-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white text-sm p-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="username" class="block text-sm mb-1">Username <span class="text-red-500">*</span></label>
                <input type="text" name="username" id="username"
                    class="w-full bg-gray-700 border border-gray-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('username') }}" required autofocus>
            </div>

            <div>
                <label for="password" class="block text-sm mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password"
                    class="w-full bg-gray-700 border border-gray-600 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <div class="flex justify-between items-center text-sm text-gray-400">
                <label><input type="checkbox" name="remember" class="mr-2 text-blue-500"> Keep me logged in</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded transition duration-200">
                Log In
            </button>
        </form>
    </div>

    {{-- RIGHT SECTION --}}
    <div class="hidden md:flex justify-center items-center bg-gray-900 text-center p-10">
        <img src="{{ asset('images/logors.png') }}" alt="Logo" class="h-10 mr-2.5">
        <h3 class="text-3xl font-bold text-white">SIGERCEP</h3>
    </div>
</div>
@endsection
