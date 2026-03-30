{{-- Approval Tiket --}}
@php
    $status = $ticket->approval?->approval_status;

    $title = match ($status) {
        'Approved' => 'Informasi Approval',
        'Rejected' => 'Informasi Rejected',
        'Need Clarification' => 'Informasi Need Clarification',
        default => 'Informasi Status Tiket',
    };

    $labelBy = match ($status) {
        'Approved' => 'Approved By',
        'Rejected' => 'Rejected By',
        'Need Clarification' => 'Requested By',
        default => 'Approved By',
    };

    $labelDate = match ($status) {
        'Approved' => 'Tanggal Approval',
        'Rejected' => 'Tanggal Rejected',
        'Need Clarification' => 'Tanggal Klarifikasi',
        default => 'Tanggal Approval',
    };

    $labelNote = match ($status) {
        'Approved' => 'Catatan Approval',
        'Rejected' => 'Alasan Penolakan',
        'Need Clarification' => 'Catatan Klarifikasi',
        default => 'Catatan Approval',
    };
@endphp

<div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6 class="mb-0 font-bold text-lg"> {{ $title }}</h6>
    </div>
    <div class="flex-auto p-6">
        @if ($ticket->approval)
            {{-- Tampilkan hasil tiket --}}
            <div class="overflow-x-auto">
                <table class="w-full table-fixed border border-gray-300 text-sm rounded-lg overflow-hidden">
                    <tbody class="divide-y divide-gray-300">

                        <tr>
                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                style="color: #7664E4 !important;">
                                Analisa IT
                            </td>
                            <td class="w-3/4 px-4 py-3 align-top">
                                {{ $ticket->approval->analysis }}
                            </td>
                        </tr>

                        <tr>
                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                style="color: #7664E4 !important;">
                                Tindakan / Rencana
                            </td>
                            <td class="w-3/4 px-4 py-3 align-top">
                                {{ $ticket->approval->action_plan }}
                            </td>
                        </tr>

                        <tr>
                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                style="color: #7664E4 !important;">
                                Estimasi Penyelesaian
                            </td>
                            <td class="w-3/4 px-4 py-3 align-top">
                                {{ $ticket->approval->duration }}
                            </td>
                        </tr>

                        <tr>
                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                style="color: #7664E4 !important;">
                                {{ $labelDate }}
                            </td>
                            <td class="w-3/4 px-4 py-3 align-top">
                                {{ \Carbon\Carbon::parse($ticket->approval->approved_at)->translatedFormat('d F Y H:i') }}
                            </td>
                        </tr>

                        @if ($ticket->approval && $ticket->approval->approval_status == 'Need Clarification')
                            <tr>
                                <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                    style="color: #7664E4 !important;">
                                    Tanggal Selesai
                                </td>
                                <td class="w-3/4 px-4 py-3 align-top">
                                    {{ $ticket->resolved_at?->translatedFormat('d F Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                style="color: #7664E4 !important;">
                                {{ $labelBy }}
                            </td>
                            <td class="w-3/4 px-4 py-3 align-top">
                                {{ ucwords(str_replace('.', ' ', $ticket->approval->approved_by)) }}
                            </td>
                        </tr>

                        @if ($ticket->approval->approval_note)
                            <tr>
                                <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                    style="color: #7664E4 !important;">
                                    {{ $labelNote }}
                                </td>
                                <td class="w-3/4 px-4 py-3 align-top">
                                    {{ $ticket->approval->approval_note }}
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

            @if (in_array(optional($ticket->approval)->approval_status, ['Approved', 'Rejected']))
                <a href="{{ route('helpdesk.index') }}"
                    class="ml-2 mt-4 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                    Kembali
                </a>
            @endif
        @else
            <p class="text-slate-600">Tiket belum diproses oleh tim IT.</p>

            <div class="mt-6">
                <a href="{{ route('helpdesk.index') }}"
                    class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                    Kembali
                </a>
            </div>
        @endif
    </div>
</div>
