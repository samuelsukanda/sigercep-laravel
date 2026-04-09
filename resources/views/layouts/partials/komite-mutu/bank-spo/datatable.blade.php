{{-- resources/views/layouts/partials/komite-mutu/bank-spo/datatable.blade.php --}}
<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="bankSpoTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama File</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Jenis SPO</th>
                <th class="px-6 py-3">Tanggal Upload</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($bankSpo as $item)
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
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->jenis_spo == 'SPO Utama' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $item->jenis_spo }}
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
                        @canAccess('bank_spo', 'update')
                        <x-button.action href="{{ route('komite-mutu.bank-spo.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('bank_spo', 'read')
                        <x-button.action href="{{ route('komite-mutu.bank-spo.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('bank_spo', 'delete')
                        <x-button.action href="{{ route('komite-mutu.bank-spo.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-row items-center justify-center gap-2">
                            <i class="fas fa-search text-lg leading-none text-gray-400 mb-1"></i>
                            <p class="leading-none ml-2 mt-2">Gunakan filter di atas untuk menampilkan data</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
