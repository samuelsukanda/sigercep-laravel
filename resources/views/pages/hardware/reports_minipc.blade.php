@extends('layouts.app')

@section('title', 'SIGERCEP - Laporan Bulanan Ceklis Mini PC & Laptop')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/loading.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/filter-responsive.css') }}">
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h3>Laporan Bulanan Ceklis Mini PC & Laptop</h3>
                </div>

                {{-- Filter Section --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="px-5 py-4">
                        <form method="GET" action="{{ route('hardware.reports.minipc') }}" id="filterForm">
                            <div class="flex flex-wrap gap-3 items-end filter-wrap">

                                {{-- Bulan --}}
                                <div class="flex flex-col mr-1 filter-item"
                                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Bulan</label>
                                    <select name="bulan"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @php
                                            $months = [
                                                '01' => 'Januari',
                                                '02' => 'Februari',
                                                '03' => 'Maret',
                                                '04' => 'April',
                                                '05' => 'Mei',
                                                '06' => 'Juni',
                                                '07' => 'Juli',
                                                '08' => 'Agustus',
                                                '09' => 'September',
                                                '10' => 'Oktober',
                                                '11' => 'November',
                                                '12' => 'Desember',
                                            ];
                                        @endphp
                                        @foreach ($months as $key => $name)
                                            <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Tahun --}}
                                <div class="flex flex-col mr-1 filter-item"
                                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Tahun</label>
                                    <select name="tahun"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = 2024; // You can adjust the start year
                                        @endphp
                                        @for ($y = $currentYear; $y >= $startYear; $y--)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Lantai --}}
                                <div class="flex flex-col mr-1 filter-item"
                                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Lantai</label>
                                    <select name="lantai"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Semua Lantai</option>
                                        @foreach ($listLantai as $lnt)
                                            <option value="{{ $lnt }}"
                                                {{ $selectedLantai == $lnt ? 'selected' : '' }}>
                                                {{ $lnt }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-end flex-1 justify-between filter-action">
                                    <div class="flex items-end">
                                        <button type="submit"
                                            class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                                            style="background-color: #7664E4 !important;">
                                            <i class="fas fa-search text-sm leading-normal"></i>
                                        </button>

                                        <a href="{{ route('hardware.reports.minipc') }}"
                                            class="btn-reset inline-flex items-center justify-center
                                                h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                                                rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                                            Reset
                                        </a>
                                    </div>

                                    <div class="flex items-end">
                                        <a href="{{ route('hardware.reports') }}"
                                            class="mr-2 inline-flex items-center justify-center
                                                h-9 px-4 text-xs font-semibold text-white uppercase
                                                rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                                            style="background-color: #7664E4 !important;">
                                            PC Set & PC AIO
                                        </a>
                                        <a href="{{ route('hardware.index') }}"
                                            class="inline-flex items-center justify-center
                                                h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                                                rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                                            Kembali
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- DataTable --}}
                <div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
                    <table
                        class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white"
                        id="datatable-reports">
                        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
                            <tr>
                                <th class="px-6 py-3 text-center">No</th>
                                <th class="px-6 py-3">Nama PC</th>
                                <th class="px-6 py-3">Jenis PC</th>
                                <th class="px-6 py-3">IP Komputer</th>
                                <th class="px-6 py-3">Lantai</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Device & Printer</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-s text-slate-500 bg-white">
                            @foreach ($pcMasters as $index => $pc)
                                @php
                                    $isChecked = isset($hardwareChecks[$pc->ip]);
                                    $hardwareId = $isChecked ? $hardwareChecks[$pc->ip]->id : null;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $pc->nama_pc }}</td>
                                    <td class="px-6 py-4 font-medium">{{ $pc->jenis_pc }}</td>
                                    <td class="px-6 py-4">{{ $pc->ip }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $pc->lantai }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($isChecked)
                                            <i class="fas fa-check text-emerald-500 text-lg" style="color: #22c55e;"
                                                title="Sudah Dicek"></i>
                                        @else
                                            <i class="fas fa-times text-red-500 text-lg" style="color: #ef4444;"
                                                title="Belum Dicek"></i>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('hardware.device-printer.show', $pc->ip) }}"
                                            class="text-indigo-500 hover:text-indigo-700 transition-colors"
                                            title="Device & Printer">
                                            <i class="fas fa-print text-lg"></i>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 space-x-2 text-center">
                                        {{-- Edit Master --}}
                                        <a href="{{ route('hardware.master-mini-pc.edit', $pc->id) }}"
                                            class="text-amber-500 hover:text-amber-700" title="Edit Master Data">
                                            <i class="fas fa-pen-to-square text-lg"></i>
                                        </a>
                                        @if ($isChecked)
                                            <a href="{{ route('hardware.show', $hardwareId) }}"
                                                class="text-blue-500 hover:text-blue-700" title="Lihat Detail">
                                                <i class="fas fa-eye text-lg"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Loading Overlay --}}
                @include('layouts.partials.hardware.loading-overlay')

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/loading-filter.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#datatable-reports')) {
                $('#datatable-reports').DataTable().destroy();
            }
            $('#datatable-reports').DataTable({
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
                initComplete: function() {
                    $(this.api().table().container()).addClass(
                        "datatable-custom-wrapper",
                    );
                },
            });
        });
    </script>
@endpush
