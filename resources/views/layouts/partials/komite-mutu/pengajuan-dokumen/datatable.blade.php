<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="pengajuanDokumenTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Judul Dokumen</th>
                <th class="px-6 py-3">Jenis Dokumen</th>
                <th class="px-6 py-3">Nomor Dokumen</th>
                <th class="px-6 py-3">Kategori Pengajuan</th>
                <th class="px-6 py-3">Tanggal Pengajuan</th>
                <th class="px-6 py-3">Diajukan Oleh</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($pengajuanDokumen as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ Str::limit($item->judul_dokumen, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->jenis_dokumen }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $item->nomor_dokumen }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->kategori_pengajuan == 'Baru' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $item->kategori_pengajuan }}
                        </span>
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $item->diajukan_oleh }}</td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('pengajuan_dokumen', 'update')
                        <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('pengajuan_dokumen', 'read')
                        <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.show', $item->id) }}"
                            icon="eye" color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('pengajuan_dokumen', 'delete')
                        <x-button.action href="{{ route('komite-mutu.pengajuan-dokumen.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data pengajuan dokumen ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
