{{-- resources/views/layouts/partials/helpdesk/admin/datatable.blade.php --}}
<div class="relative overflow-x-auto shadow-md rounded-lg px-2 bg-white dark:text-white">
    <table id="ticketTable" class="datatable-custom min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
        <thead class="text-xs text-slate-500 uppercase bg-white dark:text-white">
            <tr>
                <th class="px-6 py-3">No. Tiket</th>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Divisi</th>
                <th class="px-6 py-3">Tanggal/Jam</th>
                <th class="px-6 py-3">Kategori</th>
                <th class="px-6 py-3">Tingkat Urgensi</th>
                <th class="px-6 py-3">Status Tiket</th>
                <th class="px-6 py-3">Status Approval</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>

        <tbody class="text-s text-slate-500 bg-white">
            @foreach ($tickets as $ticket)
            <tr>
                <td class="px-6 py-4 font-semibold">{{ $ticket->ticket_number }}</td>
                <td class="px-6 py-4">
                    {{ ucwords(str_replace('.', ' ', $ticket->user->name ?? '-')) }}
                </td>
                <td class="px-6 py-4">
                    {{ $ticket->user->unit ?? '-' }}
                </td>
                <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($ticket->created_at)->timestamp }}">
                    {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y H:i') }}
                </td>
                <td class="px-6 py-4">{{ $ticket->category ?? '-' }}</td>
                <td class="px-6 py-4">
                    <x-badge.urgency-badge :urgency="$ticket->urgency" />
                </td>
                <td class="px-6 py-4">
                    <x-badge.status-badge :status="$ticket->status" />
                </td>
                <td class="px-6 py-4">
                    <x-badge.status-approval-badge :status="$ticket->approval?->approval_status ?? 'Pending'" />
                </td>
                <td class="px-6 py-4 space-x-2 text-center">
                    <x-button.action href="{{ route('helpdesk.show', $ticket->id) }}" icon="eye"
                        color="emerald" title="Lihat Data" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>