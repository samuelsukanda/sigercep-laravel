<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="manajemenRisikoTable" class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Unit</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Uraian Risiko</th>
                <th class="px-6 py-3">Dampak</th>
                <th class="px-6 py-3">Kemungkinan</th>
                <th class="px-6 py-3">Nilai Risiko</th>
                <th class="px-6 py-3">Tingkat Risiko</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-s text-slate-500 bg-white">
            @forelse ($mutu as $item)
                @php
                    $nilai = $item->dampak * $item->kemungkinan;
                    $tingkatRisiko =
                        $nilai <= 4 ? 'Rendah' : ($nilai <= 9 ? 'Sedang' : ($nilai <= 16 ? 'Tinggi' : 'Sangat Tinggi'));
                    $badgeColor =
                        $tingkatRisiko == 'Rendah'
                            ? 'bg-green-100 text-green-800'
                            : ($tingkatRisiko == 'Sedang'
                                ? 'bg-yellow-100 text-yellow-800'
                                : ($tingkatRisiko == 'Tinggi'
                                    ? 'bg-orange-100 text-orange-800'
                                    : 'bg-red-100 text-red-800'));
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ ucfirst(strtolower($item->nama)) }}</td>
                    <td class="px-6 py-4">{{ $item->unit }}</td>
                    <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($item->tanggal)->timestamp }}">
                        <div class="flex flex-col">
                            <span>{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">{{ Str::limit($item->uraian, 50) }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->dampak }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $item->kemungkinan }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center font-bold">
                        {{ $nilai }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $badgeColor }}">
                            {{ $tingkatRisiko }}
                        </span>
                    </td>
                    <td class="px-6 py-4 space-x-2 text-center">
                        @canAccess('manajemen_risiko', 'update')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.edit', $item->id) }}"
                            icon="pen-to-square" color="emerald" title="Edit" />
                        @endcanAccess

                        @canAccess('manajemen_risiko', 'read')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.show', $item->id) }}"
                            icon="eye" color="emerald" title="Lihat Data" />
                        @endcanAccess

                        @canAccess('manajemen_risiko', 'delete')
                        <x-button.action href="{{ route('komite-mutu.manajemen-risiko.destroy', $item->id) }}"
                            icon="trash" color="red" type="button" method="DELETE" title="Hapus" />
                        @endcanAccess
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-inbox text-4xl text-gray-400"></i>
                            <p>Tidak ada data manajemen risiko ditemukan</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
