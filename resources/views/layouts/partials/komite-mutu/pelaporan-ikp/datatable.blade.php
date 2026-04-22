<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="pelaporanIkpTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama Pasien</th>
                <th class="px-6 py-3">No. Rekam Medis</th>
                <th class="px-6 py-3">Tanggal Kejadian</th>
                <th class="px-6 py-3">Jenis Kelamin</th>
                <th class="px-6 py-3">Kelompok Umur</th>
                <th class="px-6 py-3">Jenis Insiden</th>
                <th class="px-6 py-3">Grading Risiko</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($pelaporanIkp as $item)
                @php
                    $gradingColor =
                        $item->grading_risiko == 'Rendah'
                            ? 'bg-green-100 text-green-800'
                            : ($item->grading_risiko == 'Sedang'
                                ? 'bg-yellow-100 text-yellow-800'
                                : ($item->grading_risiko == 'Tinggi'
                                    ? 'bg-orange-100 text-orange-800'
                                    : 'bg-red-100 text-red-800'));
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucfirst(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->no_rm }}</td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal_kejadian)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $item->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                            {{ $item->jenis_kelamin }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $item->kelompok_umur }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                            {{ $item->jenis_insiden }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $gradingColor }}">
                            {{ $item->grading_risiko }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('pelaporan_ikp', 'update')
                        <x-button.action href="{{ route('komite-mutu.pelaporan-ikp.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('pelaporan_ikp', 'read')
                        <x-button.action href="{{ route('komite-mutu.pelaporan-ikp.show', $item->id) }}" icon="eye"
                            color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('pelaporan_ikp', 'delete')
                        <x-button.action href="{{ route('komite-mutu.pelaporan-ikp.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data pelaporan IKP ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
