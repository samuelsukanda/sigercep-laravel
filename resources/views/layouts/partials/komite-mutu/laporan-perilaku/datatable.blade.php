<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="laporanPerilakuTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">NIK</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Kategori Laporan</th>
                <th class="px-6 py-3">Keterangan Perilaku</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($laporanPerilaku as $item)
                @php
                    $kategoriColor =
                        $item->kategori_laporan == 'Pelanggaran Ringan'
                            ? 'bg-yellow-100 text-yellow-800'
                            : ($item->kategori_laporan == 'Pelanggaran Sedang'
                                ? 'bg-orange-100 text-orange-800'
                                : ($item->kategori_laporan == 'Pelanggaran Berat'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-blue-100 text-blue-800'));
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucwords(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->nik }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $kategoriColor }}">
                            {{ $item->kategori_laporan }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($item->keterangan_perilaku, 50) }}</td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('laporan_perilaku', 'update')
                        <x-button.action href="{{ route('komite-mutu.laporan-perilaku.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('laporan_perilaku', 'read')
                        <x-button.action href="{{ route('komite-mutu.laporan-perilaku.show', $item->id) }}"
                            icon="eye" color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('laporan_perilaku', 'delete')
                        <x-button.action href="{{ route('komite-mutu.laporan-perilaku.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data laporan perilaku ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
