<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="komiteMedikTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama File</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Tanggal Upload</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($komiteMedik as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-500 text-lg"></i>
                            {{ $item->file_pdf }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $item->unit }}
                        </span>
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->created_at)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</span>
                            <span
                                class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}
                                WIB</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('komite_medik', 'update')
                        <x-button.action href="{{ route('komite-medik.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('komite_medik', 'read')
                        <x-button.action href="{{ route('komite-medik.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('komite_medik', 'delete')
                        <x-button.action href="{{ route('komite-medik.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data komite medik ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
