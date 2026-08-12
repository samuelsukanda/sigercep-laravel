@extends('layouts.app')

@section('title', 'SIGERCEP - Struktur Organisasi')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Struktur Organisasi</h6>
        </div>

        <div class="w-full overflow-hidden">
            <img src="{{ asset('images/struktur-organisasi.png') }}" alt="struktur-organisasi"
                class="w-full h-auto object-contain">
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
@endpush
