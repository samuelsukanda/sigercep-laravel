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
                                <p class="text-slate-600">{{ ucfirst($ticket->user->name) }}</p>
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

                {{-- Informasi Approval --}}
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
                                                {{ $ticket->approval->approved_by }}
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

                            @if (!$ticket->updates || $ticket->updates->count() == 0)
                                <div class="mt-6">
                                    <a href="{{ route('helpdesk.index') }}"
                                        class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                        Kembali
                                    </a>
                                </div>
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
                                <a href="{{ route('helpdesk.index') }}"
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
@endsection
