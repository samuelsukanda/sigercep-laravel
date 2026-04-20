<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="outsourcingTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Tujuan Unit</th>
                <th class="px-6 py-3">Area</th>
                <th class="px-6 py-3">Jam</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Kendala</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($komplain as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucwords(str_replace('.', ' ', $item->nama ?? '-')) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">{{ $item->tujuan_unit }}</td>
                    <td class="px-6 py-4">{{ $item->area ?? '-' }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jam)->format('H:i') }}
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4">{{ strtolower($item->kendala) }}</td>
                    <td class="px-6 py-4">
                        <x-badge.status-badge :status="$item->status" />
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('outsourcing_vendor', 'update')
                        <x-button.action href="{{ route('komplain.outsourcing-vendor.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('outsourcing_vendor', 'read')
                        <x-button.action href="{{ route('komplain.outsourcing-vendor.show', $item->id) }}"
                            icon="eye" color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('outsourcing_vendor', 'delete')
                        <x-button.action href="{{ route('komplain.outsourcing-vendor.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data komplain ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
