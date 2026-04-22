{{-- DataTable --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6 class="mb-0 font-bold text-lg">Detail Tiket</h6>

        <span class="font-normal text-gray-500">
            ({{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }}
            s/d
            {{ request('periode_sampai', now()->format('d-m-Y')) }})
        </span>
    </div>
    <div class="p-4 overflow-x-auto">
        <table id="ticketTable" class="datatable-custom w-full text-sm">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">No. Tiket
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">
                        Tanggal/Jam
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Nama
                        Pelapor
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Divisi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Kategori
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Tingkat
                        Urgensi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600">Deskripsi</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                        Tiket</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Status
                        Approval
                    </th>
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Approved
                        By</th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Estimasi
                    </th>
                    <th class="text-left py-3 px-3 font-semibold text-slate-600 whitespace-nowrap">Selesai
                    </th>
                </tr>
            </thead>
            <tbody>
                @if (!$isFiltered)
                    <tr>
                        <td colspan="12" class="text-center py-6 text-gray-400">
                            Silakan pilih filter lalu klik <b>Cari</b>
                        </td>
                    </tr>
                @elseif ($tickets->count() == 0)
                    <tr>
                        <td colspan="12" class="text-center py-6 text-red-400">
                            Data tidak ditemukan
                        </td>
                    </tr>
                @else
                    @foreach ($tickets as $ticket)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-100">
                            <td class="py-3 px-3 whitespace-nowrap">
                                <span class="font-semibold text-blue-600">{{ $ticket->ticket_number }}</span>
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ ucwords(str_replace('.', ' ', $ticket->user->name ?? '-')) }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->user->unit ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->category ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->urgency ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 max-w-[200px] truncate text-s">
                                {{ \Str::limit($ticket->description, 40) }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap text-s text-gray-600">
                                {{ $ticket->status ?? 'Closed' }}
                            </td>
                            <td class="py-3 px-3 whitespace-nowrap text-s text-gray-600">
                                {{ $ticket->approval?->approval_status ?? 'Pending' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->approval?->approved_by ? ucwords(str_replace('.', ' ', $ticket->approval?->approved_by)) : '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->approval?->duration ?? '-' }}
                            </td>
                            <td class="py-3 px-3 text-gray-500 whitespace-nowrap text-s">
                                {{ $ticket->resolved_at ? $ticket->resolved_at->format('d-m-Y') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
