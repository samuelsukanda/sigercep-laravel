<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="komplainTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Tujuan Unit</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Kendala</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="text-s text-slate-500 bg-white">
            @foreach ($komplain as $item)
                <tr>
                    <td class="px-6 py-4 font-medium">{{ ucfirst(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">{{ $item->tujuan_unit }}</td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4">{{ strtolower($item->kendala) }}</td>
                    <td class="px-6 py-4">
                        <x-badge.status-badge :status="$item->status" />
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('komplain_ipsrs', 'update')
                        <x-button.action href="{{ route('komplain.ipsrs.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('komplain_ipsrs', 'read')
                        <x-button.action href="{{ route('komplain.ipsrs.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('komplain_ipsrs', 'delete')
                        <x-button.action href="{{ route('komplain.ipsrs.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
