{{-- Filter Section --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="px-5 py-4">
        <form method="GET" action="{{ route('reports.summary') }}">
            <div class="flex flex-wrap gap-3 items-end filter-wrap">

                {{-- Periode Dari --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Dari</label>
                    <input type="text" name="periode_dari"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
                        value="{{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}"
                        placeholder="Pilih tanggal">
                </div>

                {{-- Periode Sampai --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:148px; flex:1 1 148px; max-width:180px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Sampai</label>
                    <input type="text" name="periode_sampai"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent flatpickr"
                        value="{{ request('periode_sampai', now()->format('d-m-Y')) }}" placeholder="Pilih tanggal">
                </div>

                {{-- Kategori --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:140px; flex:1 1 140px; max-width:170px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Kategori</label>
                    <select name="kategori"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Semua</option>
                        <option value="Hardware" {{ request('kategori') == 'Hardware' ? 'selected' : '' }}>
                            Hardware
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
                <div class="flex flex-col mr-1 filter-item" style="min-width:140px; flex:1 1 140px; max-width:170px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Status Tiket</label>
                    <select name="status_tiket"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Semua</option>
                        <option value="Open" {{ request('status_tiket') == 'Open' ? 'selected' : '' }}>
                            Open
                        </option>
                        <option value="In Progress" {{ request('status_tiket') == 'In Progress' ? 'selected' : '' }}>In
                            Progress
                        </option>
                        <option value="Closed" {{ request('status_tiket') == 'Closed' ? 'selected' : '' }}>
                            Closed</option>
                        <option value="Done" {{ request('status_tiket') == 'Done' ? 'selected' : '' }}>
                            Done</option>
                    </select>
                </div>

                {{-- Status Approval --}}
                <div class="flex flex-col mr-1 filter-item" style="min-width:160px; flex:1 1 160px; max-width:200px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Status Approval</label>
                    <select name="status_approval"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                        <option value="">Semua</option>
                        <option value="Approved" {{ request('status_approval') == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="Rejected" {{ request('status_approval') == 'Rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                        <option value="Need Clarification"
                            {{ request('status_approval') == 'Need Clarification' ? 'selected' : '' }}>
                            Need
                            Clarification</option>
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end filter-action">

                    <!-- Button Cari -->
                    <button type="submit"
                        class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                        style="background-color: #7664E4 !important;">

                        <!-- Flat Search Icon -->
                        <i class="fas fa-search text-sm leading-normal"></i>
                    </button>

                    <!-- Button Reset -->
                    <a href="{{ route('reports.summary') }}"
                        class="btn-reset mr-1 inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                            rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                        Reset
                    </a>

                    <!-- Button Export -->
                    @php
                        $disableExport = !$isFiltered || $tickets->count() == 0;
                    @endphp

                    <a href="{{ $disableExport ? '#' : route('reports.export', request()->query()) }}"
                        title="{{ $disableExport ? 'Tidak ada data untuk diexport' : 'Export ke Excel' }}"
                        class="inline-flex items-center gap-1.5
                            h-9 px-4
                            text-xs font-semibold text-white uppercase
                            rounded-lg shadow-md
                            bg-gradient-to-tl from-emerald-500 to-teal-400
                            transition-all
                            {{ !$disableExport ? 'hover:shadow-sm active:opacity-85' : '' }}"
                        style="{{ $disableExport ? 'cursor:not-allowed;' : '' }}"
                        {{ $disableExport ? 'onclick=return false;' : '' }}>

                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
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
