@extends('layouts.app')

@section('title', 'SIGERCEP - Knowledge Base')

{{-- Style --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter-responsive.css') }}">
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Daftar Knowledge Base</h3>
                </div>

                @if (session('success'))
                    <div class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter Section --}}
                @include('layouts.partials.knowledge-base.filter')

                {{-- Card Grid --}}
                @include('layouts.partials.knowledge-base.card-grid')

                {{-- Loading Overlay --}}
                @include('layouts.partials.knowledge-base.loading-overlay')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Trigger loading overlay on filter form submit
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function() {
                    const overlay = document.getElementById('loadingOverlay');
                    if (overlay) {
                        overlay.style.display = 'flex';
                    }
                });
            }
        });
    </script>
@endpush
