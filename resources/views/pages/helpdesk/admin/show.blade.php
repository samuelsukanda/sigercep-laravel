@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Detail Tiket #{{ $ticket->ticket_number }}</h3>
                </div>
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">

                    {{-- Informasi Tiket --}}
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Informasi Tiket</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="flex flex-wrap -mx-3">

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold" style="color: #7664E4 !important;">No.
                                    Tiket</label>
                                <p class="text-slate-600">{{ $ticket->ticket_number }}</p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold" style="color: #7664E4 !important;">Nama
                                    Pelapor</label>
                                <p class="text-slate-600"> {{ ucfirst($ticket->user->name) }}</p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold"
                                    style="color: #7664E4 !important;">Divisi</label>
                                <p class="text-slate-600">{{ $ticket->unit_name }}</p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold"
                                    style="color: #7664E4 !important;">Tanggal/Jam</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y H:i') }}
                                </p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold"
                                    style="color: #7664E4 !important;">Kategori</label>
                                <p class="text-slate-600">{{ $ticket->category }}</p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold" style="color: #7664E4 !important;">Tingkat
                                    Urgensi</label>
                                <p class="text-slate-600">{{ $ticket->urgency }}</p>
                            </div>

                            <div class="w-full md:w-1/2 xl:w-1/3 px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold" style="color: #7664E4 !important;">Status
                                    Tiket</label>
                                <p class="text-slate-600">{{ $ticket->status ?? '-' }}</p>
                            </div>

                            <div class="w-full px-3 mb-4">
                                <label class="block mb-1 text-sm font-semibold"
                                    style="color: #7664E4 !important;">Deskripsi</label>
                                <p class="text-slate-600">{{ $ticket->description }}</p>
                            </div>

                            <div class="w-full px-3">
                                <label class="block mb-1 text-sm font-semibold" style="color: #7664E4 !important;">Lampiran
                                    Pendukung</label>
                                @if ($ticket->attachment)
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}"
                                        class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-1/3" />
                                @else
                                    <p class="mt-2 text-sm text-slate-600">Tidak ada lampiran pendukung</p>
                                @endif
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Approval Tiket --}}
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Informasi Approval</h6>
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
                                                Tanggal Approval
                                            </td>
                                            <td class="w-3/4 px-4 py-3 align-top">
                                                {{ \Carbon\Carbon::parse($ticket->approval->approved_at)->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                                style="color: #7664E4 !important;">
                                                Tanggal Selesai
                                            </td>
                                            <td class="w-3/4 px-4 py-3 align-top">
                                                {{ \Carbon\Carbon::parse($ticket->resolved_at)->translatedFormat('d F Y H:i') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                                style="color: #7664E4 !important;">
                                                Approved By
                                            </td>
                                            <td class="w-3/4 px-4 py-3 align-top">
                                                {{ ucwords(str_replace('.', ' ', $ticket->approval->approved_by)) }}
                                            </td>
                                        </tr>

                                        @if ($ticket->approval->approval_note)
                                            <tr>
                                                <td class="w-1/4 px-4 py-3 font-semibold bg-gray-50 border-r border-gray-300 align-top"
                                                    style="color: #7664E4 !important;">
                                                    Catatan Approval
                                                </td>
                                                <td class="w-3/4 px-4 py-3 align-top">
                                                    {{ $ticket->approval->approval_note }}
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        @else
                            {{-- Form Approval --}}
                            <form id="form" action="{{ route('admin.helpdesk.approve', $ticket->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    {{-- Nama Pelapor --}}
                                    <x-form.input name="analysis" label="Analisa IT" :value="old('analysis', $tickets->analysis ?? '')" required />

                                    {{-- Tindakan / Rencana Penanganan --}}
                                    <x-form.input name="action_plan" label="Tindakan / Rencana Penanganan" :value="old('action_plan', $tickets->action_plan ?? '')"
                                        required />

                                    {{-- Estimasi Penyelesaian --}}
                                    <x-form.input name="estimated_completion" label="Estimasi Penyelesaian"
                                        :value="old('estimated_completion', $tickets->estimated_completion ?? '')" id="estimated_completion"
                                        placeholder="Pilih Estimasi Penyelesaian" required />

                                    {{-- Status Approval --}}
                                    <x-form.select name="approval_status" id="approval_status" label="Status Approval"
                                        :options="config('units.status')" :selected="old('approval_status', $tickets->approval_status ?? '')" required />

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

                {{-- Update Status Penanganan  --}}
                @php
                    $allowedStatus = ['Approved', 'Need Clarification', 'Rejected'];
                @endphp

                @if ($ticket->approval && in_array($ticket->approval->approval_status, $allowedStatus) && $ticket->status != 'Closed')
                    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Update Status Penanganan</h6>
                        </div>
                        <div class="flex-auto p-6">
                            <form id="form" action="{{ route('admin.helpdesk.update-status', $ticket->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Update Status --}}
                                    <x-form.select name="status" label="Status" :options="config('units.approval_status')" :selected="old('status', $tickets->status ?? '')"
                                        required />

                                    {{-- Catatan Penanganan --}}
                                    <x-form.textarea name="note" label="Catatan Penanganan" :value="old('note', $tickets->note ?? '')"
                                        required />

                                    <div class="mt-6">
                                        <x-button.submit type="submit">Update</x-button.submit>

                                        @if (!$ticket->updates || $ticket->updates->count() == 0)
                                            <a href="{{ route('admin.helpdesk.index') }}"
                                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                                Kembali
                                            </a>
                                        @endif
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Riwayat Penanganan --}}
                @if ($ticket->updates && $ticket->updates->count())
                    <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                        <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                            <h6 class="mb-0 font-bold text-lg">Riwayat Penanganan</h6>
                        </div>
                        <div class="flex-auto p-6">
                            <div class="space-y-4">
                                @foreach ($ticket->updates as $update)
                                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 mb-4">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="px-2 py-1 text-xs font-semibold bg-gray-300 rounded">
                                                {{ $update->created_at->format('d-m-Y H:i') }}
                                            </span>

                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded 
                                                    {{ $update->status == 'In Progress' ? 'bg-blue-500 text-white' : 'bg-gray-400 text-white' }}">
                                                {{ $update->status }}
                                            </span>
                                        </div>

                                        <p class="text-sm text-slate-600">
                                            {{ $update->note }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                <a href="{{ route('admin.helpdesk.index') }}"
                                    class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Kembali
                                </a>
                            </div>

                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @if (!$ticket->approval)
        @push('scripts')
            <script>
                $(document).ready(function() {

                    function toggleApprovalNote() {
                        let val = $('#approval_status').val();
                        let container = $('#approval_note_container');
                        let noteField = $('#approval_note');

                        if (val === 'Rejected' || val === 'Need Clarification') {
                            container.removeClass('hidden');
                            noteField.prop('required', true);
                        } else {
                            container.addClass('hidden');
                            noteField.prop('required', false);
                            noteField.val('');
                        }
                    }

                    $('#approval_status').on('change', function() {
                        toggleApprovalNote();
                    });

                    toggleApprovalNote();

                });
            </script>
        @endpush
    @endif
@endsection
