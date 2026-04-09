@extends('layouts.app')

{{-- Style --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Daftar Tiket Helpdesk</h3>
                </div>

                {{-- Filter Section --}}
                @include('layouts.partials.helpdesk.user.filter')

                {{-- DataTable --}}
                @include('layouts.partials.helpdesk.user.datatable')

                {{-- Loading Overlay --}}
                @include('layouts.partials.helpdesk.user.loading-overlay')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-helpdesk.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script>
        $.fn.dataTable.ext.errMode = "none";

        // Filter
        document.addEventListener("DOMContentLoaded", function() {
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
            const sampaiValue = "{{ request('periode_sampai') }}";

            if (dariValue) dari.setDate(dariValue);
            if (sampaiValue) sampai.setDate(sampaiValue);
            if (dariValue && sampaiValue) {
                sampai.set("minDate", dariValue);
                dari.set("maxDate", sampaiValue);
            }
        });
    </script>
@endpush
