{{-- Approval Tiket --}}
<div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
    @php
        $status = $ticket->approval?->approval_status;

        $title = match ($status) {
            'Approved' => 'Informasi Approval',
            'Rejected' => 'Informasi Rejected',
            'Need Clarification' => 'Informasi Need Clarification',
            default => 'Informasi Approval',
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

    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
        <h6 class="mb-0 font-bold text-lg">{{ $title }}</h6>
    </div>
    <div class="flex-auto p-6">
        @if ($ticket->approval)
            {{-- Tampilkan hasil approval --}}
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
                <a href="{{ route('admin.helpdesk.index') }}"
                    class="ml-2 mt-4 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                    Kembali
                </a>
            @endif
        @else
            {{-- Form Approval --}}
            <form id="form" action="{{ route('admin.helpdesk.approve', $ticket->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

                    {{-- Analisa IT --}}
                    <x-form.input name="analysis" label="Analisa IT" :value="old('analysis', $tickets->analysis ?? '')" required />

                    {{-- Tindakan / Rencana Penanganan --}}
                    <x-form.input name="action_plan" label="Tindakan / Rencana Penanganan" :value="old('action_plan', $tickets->action_plan ?? '')" required />

                    {{-- Kategori --}}
                    <x-form.select name="category" id="category" label="Kategori" :options="config('units.category')" :selected="old('category', $tickets->category ?? '')"
                        required />

                    {{-- Tingkat Urgensi --}}
                    <x-form.select name="urgency" label="Tingkat Urgensi" :options="config('units.urgency')" :selected="old('urgency', $tickets->urgency ?? '')"
                        required />

                    {{-- Estimasi Penyelesaian --}}
                    <x-form.input name="estimated_completion" label="Estimasi Penyelesaian" :value="old('estimated_completion', $tickets->estimated_completion ?? '')"
                        placeholder="Pilih Estimasi Penyelesaian" />

                    {{-- Status Approval --}}
                    <x-form.select name="approval_status" id="approval_status" label="Status Approval" :options="config('units.status')"
                        :selected="old('approval_status', $tickets->approval_status ?? '')" required />

                    <div id="approval_note_container" class="hidden md:col-span-2">
                        <x-form.textarea name="approval_note" id="approval_note" label="Catatan Approval"
                            :value="old('approval_note', $tickets->approval_note ?? '')" />
                    </div>

                </div>

                <x-button.submit type="submit">Simpan</x-button.submit>
                <a href="{{ route('admin.helpdesk.index') }}"
                    class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                    Kembali
                </a>

            </form>
        @endif
    </div>
</div>
