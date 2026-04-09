<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="laporanAsetRusakTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Nama Aset</th>
                <th class="px-6 py-3">Lokasi Aset</th>
                <th class="px-6 py-3">Kondisi Aset</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($pengadaan as $item)
                @php
                    $kondisiColor =
                        $item->kondisi_aset == 'Rusak Ringan'
                            ? 'bg-yellow-100 text-yellow-800'
                            : ($item->kondisi_aset == 'Rusak Sedang'
                                ? 'bg-orange-100 text-orange-800'
                                : ($item->kondisi_aset == 'Rusak Berat'
                                    ? 'bg-red-100 text-red-800'
                                    : ($item->kondisi_aset == 'Rusak Total'
                                        ? 'bg-red-200 text-red-900'
                                        : 'bg-green-100 text-green-800')));
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucfirst(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ strtolower($item->nama_aset) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ strtolower($item->lokasi_aset) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $kondisiColor }}">
                            {{ $item->kondisi_aset }}
                        </span>
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('laporan_aset_rusak', 'update')
                        <x-button.action href="{{ route('pengadaan-aset.laporan-aset-rusak.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('laporan_aset_rusak', 'read')
                        <x-button.action href="{{ route('pengadaan-aset.laporan-aset-rusak.show', $item->id) }}"
                            icon="eye" color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('laporan_aset_rusak', 'delete')
                        <x-button.action href="{{ route('pengadaan-aset.laporan-aset-rusak.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data laporan aset rusak ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
