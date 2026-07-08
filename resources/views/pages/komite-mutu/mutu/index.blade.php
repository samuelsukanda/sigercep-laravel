@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Indikator Mutu')

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
                    <h3>Daftar Mutu</h3>
                </div>

                @if (session('success'))
                    <div
                        class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter Section --}}
                @include('layouts.partials.komite-mutu.mutu.filter')

                {{-- DataTable --}}
                @include('layouts.partials.komite-mutu.mutu.datatable')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-mutu.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script src="{{ asset('assets/js/alert-delete.js') }}"></script>
    <script>
        $.fn.dataTable.ext.errMode = "none";
    </script>
@endpush
