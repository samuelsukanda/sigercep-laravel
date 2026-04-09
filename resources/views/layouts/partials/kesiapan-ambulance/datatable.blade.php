{{-- resources/views/layouts/partials/kesiapan-ambulance/datatable.blade.php --}}
<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="kesiapanAmbulanceTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Mobil Ambulance</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Perawat</th>
                <th class="px-6 py-3">Kondisi Mobil</th>
                <th class="px-6 py-3">Kondisi Driver</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($ambulance as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-ambulance text-blue-500 text-lg"></i>
                            {{ $item->mobil_ambulance }}
                        </div>
                    </td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ $item->perawat }}</td>
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->kondisi_mobil == 'Baik' ? 'bg-green-100 text-green-800' : ($item->kondisi_mobil == 'Rusak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $item->kondisi_mobil }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->kondisi_driver == 'Siap' ? 'bg-green-100 text-green-800' : ($item->kondisi_driver == 'Tidak Siap' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $item->kondisi_driver }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('kesiapan_ambulance', 'update')
                        <x-button.action href="{{ route('kesiapan-ambulance.edit', $item->id) }}" icon="pen-to-square"
                            color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('kesiapan_ambulance', 'read')
                        <x-button.action href="{{ route('kesiapan-ambulance.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('kesiapan_ambulance', 'delete')
                        <x-button.action href="{{ route('kesiapan-ambulance.destroy', $item->id) }}" icon="trash"
                            color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data kesiapan ambulance ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
