<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="mutuTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Judul Indikator</th>
                <th class="px-6 py-3">Periode</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Penanggung Jawab Data</th>
                <th class="px-6 py-3">Numerator</th>
                <th class="px-6 py-3">Penumerator</th>
                <th class="px-6 py-3">Capaian</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($mutu as $item)
                @php
                    $capaianValue = is_numeric($item->capaian) ? (float) $item->capaian : 0;
                    $badgeColor =
                        $capaianValue >= 80
                            ? 'bg-green-100 text-green-800'
                            : ($capaianValue >= 60
                                ? 'bg-yellow-100 text-yellow-800'
                                : 'bg-red-100 text-red-800');
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ Str::limit($item->indikator, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $item->periode }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">{{ $item->pj_data }}</td>
                    <td class="px-6 py-4">{{ $item->numerator }}</td>
                    <td class="px-6 py-4">{{ $item->penumerator }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                            {{ $item->capaian }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('mutu', 'update')
                        <x-button.action href="{{ route('komite-mutu.mutu.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('mutu', 'read')
                        <x-button.action href="{{ route('komite-mutu.mutu.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('mutu', 'delete')
                        <x-button.action href="{{ route('komite-mutu.mutu.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data mutu ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
