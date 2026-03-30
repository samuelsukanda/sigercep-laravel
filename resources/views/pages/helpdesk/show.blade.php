@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Detail Tiket #{{ $ticket->ticket_number }}</h3>
                </div>
                {{-- Informasi Tiket --}}
                @include('layouts.partials.helpdesk.informasi-tiket')

                {{-- Approval Tiket --}}
                @include('layouts.partials.helpdesk.approval-tiket')

                {{-- Riwayat Penanganan --}}
                @include('layouts.partials.helpdesk.riwayat-penanganan')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/preview.js') }}"></script>
@endpush
