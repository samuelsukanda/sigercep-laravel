@extends('layouts.app')

@section('title', 'SIGERCEP - Change Request')

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
                <x-page-header icon="fa-code-branch" title="Change Request" subtitle="Kelola permintaan fitur dan perubahan sistem" />

                {{-- Filter Section --}}
                @include('layouts.partials.change-request.filter')

                {{-- DataTable --}}
                @include('layouts.partials.change-request.datatable')

                {{-- Loading Overlay --}}
                @include('layouts.partials.change-request.loading-overlay')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-change-request.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
    <script>
        $.fn.dataTable.ext.errMode = "none";

        // Filter
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('#filter_status_dokumen').select2({
                    placeholder: "Pilih Status Dokumen",
                    allowClear: true,
                    width: '100%'
                });
                $('#filter_status_pengerjaan').select2({
                    placeholder: "Pilih Status Pengerjaan",
                    allowClear: true,
                    width: '100%'
                });
            }

            var dari = flatpickr("input[name='periode_dari']", {
                dateFormat: "d-m-Y",
                allowInput: false,
                onChange: function(selectedDates, dateStr, instance) {
                    sampai.set("minDate", dateStr);
                },
            });

            var sampai = flatpickr("input[name='periode_sampai']", {
                dateFormat: "d-m-Y",
                allowInput: false,
                onChange: function(selectedDates, dateStr, instance) {
                    dari.set("maxDate", dateStr);
                },
            });

            const dariValue = "{{ request('periode_dari') }}";
            const sampaiValue = "{{ request('periode_sampai', now()->format('d-m-Y')) }}";

            dari.setDate(dariValue);
            sampai.setDate(sampaiValue);
            sampai.set("minDate", dariValue);
            dari.set("maxDate", sampaiValue);
        });
    </script>
@endpush
