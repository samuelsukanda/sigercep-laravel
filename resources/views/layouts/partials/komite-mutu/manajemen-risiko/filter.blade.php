<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="px-5 py-4">
        <form method="GET" action="{{ route('komite-mutu.manajemen-risiko.index') }}" id="filterForm">
            <div class="flex flex-wrap gap-3 items-end filter-wrap">

                {{-- Filter Unit --}}
                <div class="flex flex-col mr-1 filter-item"
                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Unit</label>
                    <select name="unit"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Unit --</option>
                        @foreach ($unitOptions as $opt)
                            <option value="{{ $opt }}" {{ $unitFilter === $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tingkat Risiko --}}
                <div class="flex flex-col mr-1 filter-item"
                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Tingkat Risiko</label>
                    <select name="tingkat"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Tingkat --</option>
                        @foreach ($tingkatOptions as $opt)
                            <option value="{{ $opt }}" {{ $tingkatFilter === $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Kode Risiko --}}
                <div class="flex flex-col mr-1 filter-item"
                    style="min-width:148px; flex:1 1 148px; max-width:180px;">
                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Kode Risiko</label>
                    <select name="kode_risiko"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Kode --</option>
                        @foreach ($kodeOptions as $opt)
                            <option value="{{ $opt }}" {{ $kodeFilter === $opt ? 'selected' : '' }}>
                                {{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-end flex-1 justify-between filter-action">
                    <div class="flex items-end">
                        <!-- Button Cari -->
                        <button type="submit"
                            class="mr-1 inline-block px-4 py-2 mb-0 text-xs font-semibold text-center text-white uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85"
                            style="background-color: #7664E4 !important;">
                            <i class="fas fa-search text-sm leading-normal"></i>
                        </button>

                        <!-- Button Reset -->
                        <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
                            class="btn-reset inline-flex items-center justify-center
                                h-9 px-4 text-xs font-semibold text-slate-700 uppercase
                                rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                            Reset
                        </a>
                    </div>

                    {{-- Tambah Data --}}
                    @canAccess('manajemen_risiko', 'create')
                    <a href="{{ route('komite-mutu.manajemen-risiko.create') }}"
                        class="inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-white uppercase
                            rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                        style="background-color: #7664E4 !important;">
                        Tambah Data
                    </a>
                    @endcanAccess
                </div>
            </div>
        </form>
    </div>
</div>
