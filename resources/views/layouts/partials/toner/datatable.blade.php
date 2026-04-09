<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="tonerTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Jenis Toner</th>
                <th class="px-6 py-3">Jumlah</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($toner as $item)
                @php
                    $tonerColor =
                        $item->toner == 'Hitam'
                            ? 'bg-gray-700 text-white'
                            : ($item->toner == 'Cyan'
                                ? 'bg-cyan-100 text-cyan-800'
                                : ($item->toner == 'Magenta'
                                    ? 'bg-pink-100 text-pink-800'
                                    : 'bg-yellow-100 text-yellow-800'));
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucfirst(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $tonerColor }}">
                            {{ $item->toner }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->jumlah }} pcs
                        </span>
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('toner', 'update')
                        <x-button.action href="{{ route('toner.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('toner', 'read')
                        <x-button.action href="{{ route('toner.show', $item->id) }}" icon="eye" color="emerald"
                            title="Lihat Data" />
                        @endcanAccess

                        @canAccess('toner', 'delete')
                        <x-button.action href="{{ route('toner.destroy', $item->id) }}" icon="trash" color="red"
                            type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data toner ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
