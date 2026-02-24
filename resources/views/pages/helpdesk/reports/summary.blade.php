{{-- resources/views/reports/summary.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Laporan Tiket Helpdesk</h3>
                </div>

                {{-- Filter Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="px-5 py-4">
                        <form method="GET" action="{{ route('reports.summary') }}">
                            <div class="flex flex-wrap gap-3 items-end">

                                {{-- Periode Dari --}}
                                <div class="flex flex-col mr-1" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Dari</label>
                                    <input type="text" name="periode_dari"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
                                        value="{{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}"
                                        placeholder="Pilih tanggal">
                                </div>

                                {{-- Periode Sampai --}}
                                <div class="flex flex-col mr-1" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Sampai</label>
                                    <input type="text" name="periode_sampai"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
                                        value="{{ request('periode_sampai', now()->format('d-m-Y')) }}"
                                        placeholder="Pilih tanggal">
                                </div>

                                {{-- Kategori --}}
                                <div class="flex flex-col mr-1" style="min-width:140px; flex:1 1 140px; max-width:170px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                                    <select name="kategori"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        <option value="">Semua</option>
                                        <option value="Hardware" {{ request('kategori') == 'Hardware' ? 'selected' : '' }}>
                                            Hardware
                                        </option>
                                        <option value="Printer" {{ request('kategori') == 'Printer' ? 'selected' : '' }}>
                                            Printer
                                        </option>
                                        <option value="Jaringan" {{ request('kategori') == 'Jaringan' ? 'selected' : '' }}>
                                            Jaringan
                                        </option>
                                        <option value="Software" {{ request('kategori') == 'Software' ? 'selected' : '' }}>
                                            Software
                                        </option>
                                        <option value="SIMRS" {{ request('kategori') == 'SIMRS' ? 'selected' : '' }}>
                                            SIMRS
                                        </option>
                                    </select>
                                </div>

                                {{-- Status Tiket --}}
                                <div class="flex flex-col mr-1" style="min-width:140px; flex:1 1 140px; max-width:170px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Status Tiket</label>
                                    <select name="status_tiket"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        <option value="">Semua</option>
                                        <option value="Open" {{ request('status_tiket') == 'Open' ? 'selected' : '' }}>
                                            Open
                                        </option>
                                        <option value="In Progress"
                                            {{ request('status_tiket') == 'In Progress' ? 'selected' : '' }}>In Progress
                                        </option>
                                        <option value="Closed" {{ request('status_tiket') == 'Closed' ? 'selected' : '' }}>
                                            Closed</option>
                                    </select>
                                </div>

                                {{-- Status Approval --}}
                                <div class="flex flex-col mr-1" style="min-width:160px; flex:1 1 160px; max-width:200px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Status Approval</label>
                                    <select name="status_approval"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                                        <option value="">Semua</option>
                                        <option value="Approved"
                                            {{ request('status_approval') == 'Approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>
                                        <option value="Rejected"
                                            {{ request('status_approval') == 'Rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                        <option value="Need Clarification"
                                            {{ request('status_approval') == 'Need Clarification' ? 'selected' : '' }}>
                                            Need
                                            Clarification</option>
                                    </select>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-end">

                                    <!-- Button Cari -->
                                    <button type="submit"
                                        class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                                        style="background-color: #7664E4 !important;">

                                        <!-- Flat Search Icon (lebih jelas) -->
                                        <i class="fas fa-search text-sm leading-normal"></i>
                                    </button>

                                    <!-- Button Reset -->
                                    <a href="{{ route('reports.summary') }}"
                                        class="mr-1 inline-flex items-center justify-center
                                                h-9 px-4
                                                text-xs font-semibold text-slate-700 uppercase
                                                rounded-lg shadow-md
                                                bg-gray-200
                                                hover:shadow-sm active:opacity-85
                                                transition-all">
                                        Reset
                                    </a>

                                    <!-- Button Export -->
                                    <a href="{{ route('reports.export', request()->query()) }}"
                                        class="inline-flex items-center gap-1.5
                                            h-9 px-4
                                            text-xs font-semibold text-white uppercase
                                            rounded-lg shadow-md
                                            bg-gradient-to-tl from-emerald-500 to-teal-400
                                            hover:shadow-sm active:opacity-85
                                            transition-all">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M4 3h9a2 2 0 012 2v10a2 2 0 01-2 2H4V3zm11 4h1v8h-1V7zM7 8l1.5 2L7 12h1.8l.7-1.2.7 1.2H12l-1.5-2L12 8h-1.8l-.7 1.2L8.8 8H7z" />
                                        </svg>

                                        Export
                                    </a>

                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- DataTable --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Tiket</h6>

                        <span class="font-normal text-gray-500">
                            ({{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}
                            s/d
                            {{ request('periode_sampai', now()->format('d-m-Y')) }})
                        </span>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        <table id="ticketTable" class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">No. Tiket
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">
                                        Tanggal/Jam
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Nama
                                        Pelapor
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Divisi
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Kategori
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600">Deskripsi</th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                                        Tiket</th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                                        Approval
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Approved
                                        By</th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Estimasi
                                    </th>
                                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Selesai
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tickets ?? [] as $ticket)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-100">
                                        <td class="py-3 px-3 whitespace-nowrap">
                                            <span class="font-semibold text-blue-600">{{ $ticket->ticket_number }}</span>
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ $ticket->created_at->format('d-m-Y H:i') }}
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ ucfirst($ticket->user->name) ?? '-' }}
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">{{ $ticket->unit }}
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ $ticket->category }}</td>
                                        <td class="py-3 px-3 text-gray-500 max-w-[200px] truncate text-s">
                                            {{ \Str::limit($ticket->description, 40) }}
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap text-s">
                                            @if ($ticket->status == 'Open')
                                                <span
                                                    class="px-2 py-1 bg-cyan-50 text-cyan-700 rounded-md text-s font-medium">Open</span>
                                            @elseif ($ticket->status == 'In Progress')
                                                <span
                                                    class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-s font-medium">In
                                                    Progress</span>
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-gray-100 text-gray-600 rounded-md text-s font-medium">Closed</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 whitespace-nowrap text-s">
                                            @if ($ticket->approval)
                                                @if ($ticket->approval->approval_status == 'Approved')
                                                    <span
                                                        class="px-2 py-1 bg-green-50 text-green-700 rounded-md text-s font-medium">Approved</span>
                                                @elseif ($ticket->approval->approval_status == 'Rejected')
                                                    <span
                                                        class="px-2 py-1 bg-red-50 text-red-600 rounded-md text-s font-medium">Rejected</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-amber-50 text-amber-700 rounded-md text-s font-medium">Need
                                                        Clarification</span>
                                                @endif
                                            @else
                                                <span
                                                    class="px-2 py-1 bg-gray-100 text-gray-500 rounded-md text-s font-medium">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ $ticket->approval->approved_by ?? '-' }}</td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ $ticket->approval->duration ?? '-' }}</td>
                                        <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                            {{ $ticket->resolved_at ? $ticket->resolved_at->format('d-m-Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Rekap Section --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                    {{-- Rekap Status Approval --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Rekap Status Approval</h6>
                        </div>
                        <div class="px-5 py-3">
                            @php
                                $allApprovalStatus = ['Approved', 'Rejected', 'Need Clarification'];
                                $approvalCollection = $approvalRecap ?? collect();
                            @endphp

                            @foreach ($allApprovalStatus as $status)
                                <div
                                    class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                                    <span class="text-sm text-gray-600">{{ $status }}</span>
                                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                                        {{ $approvalCollection->get($status, 0) }}
                                    </span>
                                </div>
                            @endforeach

                            {{-- Total seluruh tiket --}}
                            <div class="flex justify-between items-center py-2.5 mt-2 pt-2">
                                <span class="text-sm font-bold text-gray-800">Total Tiket Masuk</span>
                                <span class="text-sm font-bold text-gray-900">{{ $totalTickets }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Rekap Kategori --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Rekap Kategori</h6>
                        </div>
                        <div class="px-5 py-3">
                            @php
                                $allCategories = ['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS'];
                                $categoryData = $categoryRecap ?? [];
                            @endphp
                            @foreach ($allCategories as $cat)
                                <div
                                    class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                                    <span class="text-sm text-gray-600">{{ $cat }}</span>
                                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                                        {{ $categoryData[$cat] ?? 0 }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Rekap Status --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Rekap Status Tiket</h6>
                        </div>
                        <div class="px-5 py-3">
                            @php
                                $allStatuses = [
                                    'Open' => ['color' => 'text-cyan-600', 'bg' => 'bg-cyan-50'],
                                    'In Progress' => ['color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
                                    'Closed' => ['color' => 'text-gray-500', 'bg' => 'bg-gray-100'],
                                    'Resolved' => ['color' => 'text-green-600', 'bg' => 'bg-green-50'],
                                    'Rata-rata Penyelesaian' => [
                                        'color' => 'text-purple-600',
                                        'bg' => 'bg-purple-50',
                                        'value' => number_format($avgResolution, 2) . ' Hari',
                                    ],
                                ];
                                $statusData = $statusRecap ?? [];
                            @endphp
                            @foreach ($allStatuses as $st => $style)
                                <div
                                    class="flex justify-between items-center py-2.5 border-b border-gray-100 last:border-0">
                                    <span class="text-sm text-gray-600">{{ $st }}</span>
                                    <span class="text-sm font-semibold text-gray-800 tabular-nums">
                                        @if ($st === 'Rata-rata Penyelesaian')
                                            {{ $style['value'] }}
                                        @else
                                            {{ $statusData[$st] ?? 0 }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    </div>
@endsection

@push('scripts')
    <script>
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
            const dariValue =
                "{{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}";
            const sampaiValue =
                "{{ request('periode_sampai', now()->format('d-m-Y')) }}";

            dari.setDate(dariValue);
            sampai.setDate(sampaiValue);
            sampai.set("minDate", dariValue);
            dari.set("maxDate", sampaiValue);
        });

        $(document).ready(function() {
            $("#ticketTable").DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"],
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya",
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                },
                order: [
                    [1, "desc"]
                ],
            });
        });
    </script>
@endpush
