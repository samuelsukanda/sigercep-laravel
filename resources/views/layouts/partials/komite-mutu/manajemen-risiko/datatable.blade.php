<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:bg-slate-850 dark:text-white mb-8">
    <table id="manajemenRisikoTable"
        class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-slate-800 dark:text-slate-300">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:bg-slate-900/30 dark:text-slate-300">
            <tr>
                <th class="px-6 py-3">No</th>
                <th class="hidden">No Urut Real</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3 min-w-[300px]">Risiko</th>
                <th class="px-6 py-3 min-w-[150px]">Kode Risiko</th>
                <th class="px-6 py-3 min-w-[300px]">Sebab</th>
                <th class="px-6 py-3 min-w-[200px]">Dampak</th>
                <th class="px-6 py-3 min-w-[200px]">Pengendalian</th>
                <th class="px-6 py-3 text-center min-w-[100px]">Tingkat<br>Analisis</th>
                <th class="px-6 py-3 min-w-[150px]">Target Waktu</th>
                <th class="px-6 py-3 text-center min-w-[100px]">Tingkat<br>Mitigasi TW 1</th>
                <th class="px-6 py-3 text-center min-w-[100px]">Tingkat<br>Mitigasi TW 2</th>
                <th class="px-6 py-3 text-center min-w-[100px]">Tingkat<br>Mitigasi TW 3</th>
                <th class="px-6 py-3 text-center min-w-[100px]">Tingkat<br>Mitigasi TW 4</th>
                <th class="px-6 py-3 text-center min-w-[120px] whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-sm text-slate-600 bg-white dark:bg-slate-850 dark:text-slate-300">
            @php
                if (!function_exists('getBadgeColor')) {
                    function getBadgeColor($tingkat)
                    {
                        if (empty($tingkat)) {
                            return 'bg-slate-100 text-slate-500';
                        }
                        $t = strtolower(trim($tingkat));
                        if (str_contains($t, 'sangat rendah')) {
                            return 'bg-green-100 text-green-700';
                        }
                        if (str_contains($t, 'rendah')) {
                            return 'bg-yellow-100 text-yellow-700';
                        }
                        if (str_contains($t, 'sangat tinggi')) {
                            return 'bg-red-200 text-red-800 font-bold';
                        }
                        if (str_contains($t, 'tinggi')) {
                            return 'bg-red-100 text-red-700';
                        }
                        if (str_contains($t, 'sedang')) {
                            return 'bg-orange-100 text-orange-700';
                        }
                        return 'bg-slate-100 text-slate-700';
                    }
                }
            @endphp
            @forelse ($risikos as $item)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4 font-medium text-center"></td>
                    <td class="hidden">{{ $item->no_urut }}</td>
                    <td class="px-6 py-4 font-bold">{{ $item->unit }}</td>
                    <td class="px-6 py-4">{{ $item->risiko }}</td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                        {{ $item->kode_risiko }}</td>
                    <td class="px-6 py-4 text-xs">{{ $item->sebab }}</td>
                    <td class="px-6 py-4 text-xs">{{ $item->dampak }}</td>
                    <td class="px-6 py-4 text-xs">
                        {{ $item->pengendalian }}
                        @if ($item->efektif)
                            <span class="block mt-1 text-emerald-600 font-semibold"><i class="fas fa-check mr-1"></i>
                                Efektif</span>
                        @endif
                        @if ($item->tidak_efektif)
                            <span class="block mt-1 text-red-500 font-semibold"><i class="fas fa-times mr-1"></i> Tidak
                                Efektif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ getBadgeColor($item->analisis_tingkat) }}">
                            {{ $item->analisis_tingkat ?? '-' }}
                        </span>
                        @if ($item->analisis_nilai)
                            <span class="block text-[10px] mt-1 text-slate-400">Nilai:
                                {{ (float) $item->analisis_nilai }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center text-xs">
                        {{ $item->target_waktu ?? '-' }}
                    </td>
                    @foreach([1, 2, 3, 4] as $tw)
                    <td class="px-6 py-4 text-center">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ getBadgeColor($item->{'mitigasi_tw'.$tw.'_tingkat'}) }}">
                            {{ $item->{'mitigasi_tw'.$tw.'_tingkat'} ?? '-' }}
                        </span>
                        @if ($item->{'mitigasi_tw'.$tw.'_nilai'})
                            <span class="block text-[10px] mt-1 text-slate-400">Nilai:
                                {{ (float) $item->{'mitigasi_tw'.$tw.'_nilai'} }}</span>
                        @endif
                    </td>
                    @endforeach
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('manajemen_risiko', 'read')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.show', $item->id) }}"
                            icon="eye" color="emerald" title="Detail" />
                        @endcanAccess

                        @canAccess('manajemen_risiko', 'update')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.edit', $item->id) }}"
                            icon="edit" color="blue" title="Edit" />
                        @endcanAccess

                        @canAccess('manajemen_risiko', 'delete')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.destroy', $item->id) }}"
                            icon="trash-alt" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                            <p>Tidak ada data daftar risiko ditemukan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
