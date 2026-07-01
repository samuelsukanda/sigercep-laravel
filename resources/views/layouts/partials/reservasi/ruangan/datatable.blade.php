<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="reservasiRuanganTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Jam Mulai</th>
                <th class="px-6 py-3">Jam Selesai</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Ruang</th>
                <th class="px-6 py-3">Approval</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($reservasi as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucwords(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_mulai)->format('H:i') }} WIB</td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_selesai)->format('H:i') }} WIB</td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $item->ruang }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <x-badge.status-approval-badge :status="$item->approval ?? 'Pending'" />
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('reservasi_ruangan', 'update')
                        <x-button.action href="{{ route('reservasi.ruangan.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('reservasi_ruangan', 'read')
                        <x-button.action href="{{ route('reservasi.ruangan.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('reservasi_ruangan', 'delete')
                        <x-button.action href="{{ route('reservasi.ruangan.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data reservasi ruangan ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
