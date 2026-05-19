<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="px-5 py-4">
        <form method="GET" action="{{ route('komite-mutu.mutu.index') }}" id="filterForm">
            <div class="flex flex-wrap gap-3 items-end ">

                {{-- Tambah Data --}}
                @canAccess('mutu', 'create')
                <a href="{{ route('komite-mutu.mutu.create') }}"
                    class="inline-flex items-center justify-center
                            h-9 px-4 text-xs font-semibold text-white uppercase
                            rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                    style="background-color: #7664E4 !important;">
                    Tambah Data
                </a>
                @endcanAccess
            </div>

        </form>
    </div>
</div>
