@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar Ceklis Hardware')

{{-- Style --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter-responsive.css') }}">
    <style>
        @media (min-width: 1280px) {
            .modal-overlay {
                left: 17rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Daftar Ceklis Hardware</h3>
                </div>

                @if (session('success'))
                    <div
                        class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Filter Section --}}
                @include('layouts.partials.hardware.filter')

                {{-- DataTable --}}
                @include('layouts.partials.hardware.datatable')

                {{-- Loading Overlay --}}
                @include('layouts.partials.hardware.loading-overlay')
            </div>
        </div>
    </div>

    @push('modals')
        <div id="generateModal" tabindex="-1" aria-hidden="true" aria-labelledby="modalTitle" role="dialog"
            class="modal-overlay hidden justify-center"
            style="
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.5);
            padding: 2rem 1rem;
            overflow-y: auto;
            align-items: flex-start;
         ">

            {{-- Dialog container --}}
            <div class="relative w-full" style="max-width: 480px; margin: 1.5rem auto;">
                <div
                    style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            ">

                    {{-- ── Header ── --}}
                    <div
                        style="
                    background: #7664E4;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    border-radius: 16px 16px 0 0;
                ">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div
                                style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                                <i class="fas fa-magic" style="color: #fff; font-size: 13px;"></i>
                            </div>
                            <div>
                                <h3 id="modalTitle"
                                    style="
                                margin: 0;
                                font-size: 14px;
                                font-weight: 700;
                                color: #fff;
                                line-height: 1.2;
                            ">
                                    Generate Ceklis Hardware</h3>
                            </div>
                        </div>
                        <button type="button"
                            onclick="document.getElementById('generateModal').classList.add('hidden'); document.getElementById('generateModal').classList.remove('flex');"
                            aria-label="Tutup modal"
                            style="
                            width: 30px; height: 30px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.15);
                            border: none;
                            cursor: pointer;
                            display: flex; align-items: center; justify-content: center;
                            transition: background 0.15s;
                            flex-shrink: 0;
                        "
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                        </button>
                    </div>

                    {{-- ── Body ── --}}
                    <form action="{{ route('hardware.generate') }}" method="POST" style="padding: 1.375rem 1.25rem 1.25rem;">
                        @csrf

                        {{-- Tanggal --}}
                        <div style="margin-bottom: 1rem;">
                            <label for="tanggal"
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                                Tanggal <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" id="tanggal" name="tanggal" required placeholder="Pilih Tanggal"
                                value="{{ date('d-m-Y') }}" class="generate-flatpickr"
                                style="
                                width: 100%;
                                box-sizing: border-box;
                                height: 38px;
                                padding: 0 11px;
                                font-size: 13.5px;
                                color: #1e293b;
                                background: #f8fafc;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                outline: none;
                                transition: border-color 0.15s, box-shadow 0.15s;
                            "
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                            @error('tanggal')
                                <span
                                    style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>


                        {{-- Lantai --}}
                        <div style="margin-bottom: 1.375rem;">
                            <label
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 8px;
                               ">
                                Lantai <span style="color: #ef4444;">*</span>
                            </label>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @foreach ($listLantai as $l)
                                    <label style="cursor: pointer;">
                                        <input type="checkbox" name="lantai[]" value="{{ $l }}"
                                            style="display: none;"
                                            onchange="
                                                var span = this.nextElementSibling;
                                                if (this.checked) {
                                                    span.style.background='#7664E4';
                                                    span.style.color='#fff';
                                                    span.style.borderColor='#7664E4';
                                                } else {
                                                    span.style.background='#f1f5f9';
                                                    span.style.color='#475569';
                                                    span.style.borderColor='#cbd5e1';
                                                }
                                            ">
                                        <span class="lantai-tile"
                                            style="
                                            display: inline-block;
                                            padding: 6px 14px;
                                            font-size: 13px;
                                            font-weight: 600;
                                            border: 1.5px solid #cbd5e1;
                                            border-radius: 8px;
                                            background: #f1f5f9;
                                            color: #475569;
                                            transition: all 0.15s;
                                            user-select: none;
                                        ">{{ $l }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('lantai')
                                <span
                                    style="font-size: 11px; color: #ef4444; margin-top: 4px; display: block;">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- ── Footer / Action buttons ── --}}
                        <div
                            style="
                        border-top: 1px solid #f1f5f9;
                        padding-top: 1rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 8px;
                        border-radius: 0 0 16px 16px;
                    ">
                            <button type="button"
                                onclick="document.getElementById('generateModal').classList.add('hidden'); document.getElementById('generateModal').classList.remove('flex');"
                                style="
                                height: 38px;
                                padding: 0 16px;
                                font-size: 13px;
                                font-weight: 600;
                                color: #64748b;
                                background: #f1f5f9;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: background 0.15s;
                            "
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                Batal
                            </button>
                            <button type="submit"
                                style="
                                height: 38px;
                                padding: 0 20px;
                                font-size: 13px;
                                font-weight: 700;
                                color: #fff;
                                background: #3b82f6;
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 7px;
                                transition: background 0.15s, box-shadow 0.15s;
                            "
                                onmouseover="this.style.background='#2563eb'; this.style.boxShadow='0 4px 12px rgba(59,130,246,0.35)'"
                                onmouseout="this.style.background='#3b82f6'; this.style.boxShadow='none'">
                                <i class="fas fa-magic" style="font-size: 12px;"></i>
                                Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpush
@endsection



@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-hardware.js') }}"></script>
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
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

            const dariValue = "{{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}";
            const sampaiValue = "{{ request('periode_sampai', now()->format('d-m-Y')) }}";

            dari.setDate(dariValue);
            sampai.setDate(sampaiValue);
            sampai.set("minDate", dariValue);
            dari.set("maxDate", sampaiValue);

            // Modal Generate Date
            flatpickr(".generate-flatpickr", {
                dateFormat: "d-m-Y",
                allowInput: false,
                defaultDate: "today"
            });
        });
    </script>
@endpush
