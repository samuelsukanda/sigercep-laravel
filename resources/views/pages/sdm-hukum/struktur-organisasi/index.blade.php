@extends('layouts.app')

@section('title', 'SIGERCEP - Struktur Organisasi')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <x-page-header icon="fa-sitemap" title="Struktur Organisasi" subtitle="Bagan struktur organisasi rumah sakit" />

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
