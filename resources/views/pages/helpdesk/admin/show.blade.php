@extends('layouts.app')

@section('title', 'SIGERCEP - Detail Tiket Helpdesk Admin')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Detail Tiket #{{ $ticket->ticket_number }}</h3>
                </div>

                {{-- Informasi Tiket --}}
                @include('layouts.partials.helpdesk.informasi-tiket-admin')

                {{-- Approval Tiket --}}
                @include('layouts.partials.helpdesk.approval-tiket-admin')

                {{-- Riwayat Penanganan --}}
                @include('layouts.partials.helpdesk.riwayat-penanganan-admin')

                {{-- Update Status Penanganan  --}}
                @include('layouts.partials.helpdesk.update-status-penanganan-admin')

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/js/preview.js') }}"></script>
    @endpush

    @if (!$ticket->approval)
        @push('scripts')
            <script src="{{ asset('assets/js/toggle-approval.js') }}"></script>
        @endpush
    @endif
@endsection
