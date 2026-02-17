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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- No. Tiket --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">No. Tiket</label>
                                <p class="text-slate-600">{{ $ticket->ticket_number }}</p>
                            </div>

                            {{-- Nama Pelapor --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama Pelapor</label>
                                <p class="text-slate-600">{{ auth()->user()->name }}</p>
                            </div>

                            {{-- Divisi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Divisi</label>
                                <p class="text-slate-600">{{ $ticket->unit }}</p>
                            </div>

                            {{-- Tanggal/Jam --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal/Jam</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y H:i') }}
                                </p>
                            </div>

                            {{-- Kategori --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kategori</label>
                                <p class="text-slate-600">{{ $ticket->category }}</p>
                            </div>

                            {{-- Tingkat Urgensi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tingkat Urgensi</label>
                                <p class="text-slate-600">{{ $ticket->urgency }}</p>
                            </div>

                            {{-- Status Tiket --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status Tiket</label>
                                <p class="text-slate-600">{{ $ticket->status ?? '-' }}</p>
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Deskripsi</label>
                                <p class="text-slate-600">{{ $ticket->description }}</p>
                            </div>

                            {{-- Lampiran Pendukung --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lampiran Pendukung</label>
                                @if ($ticket->attachment)
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Foto ticket"
                                        class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-1/2" />
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Status Approval --}}
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">
                                        Status Approval
                                    </label>
                                    <p class="text-slate-600">{{ $ticket->approval->approval_status }}</p>
                                </div>

                                {{-- Approved By --}}
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">
                                        Approved By
                                    </label>
                                    <p class="text-slate-600">{{ $ticket->approval->approved_by }}</p>
                                </div>

                                {{-- Tanggal/Jam --}}
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal/Jam</label>
                                    <p class="text-slate-600">
                                        {{ \Carbon\Carbon::parse($ticket->approval->approved_at)->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>

                                {{-- Estimasi Penyelesaian --}}
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">
                                        Estimasi Penyelesaian
                                    </label>
                                    <p class="text-slate-600">
                                        {{ $ticket->approval->duration }}
                                    </p>
                                </div>

                                {{-- Tanggal Selesai --}}
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal
                                        Selesai</label>
                                    <p class="text-slate-600">
                                        {{ \Carbon\Carbon::parse($ticket->approval->estimated_completion)->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>

                                {{-- Analisa IT --}}
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">
                                        Analisa IT
                                    </label>
                                    <p class="text-slate-600">{{ $ticket->approval->analysis }}</p>
                                </div>

                                {{-- Tindakan / Rencana --}}
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">
                                        Tindakan / Rencana
                                    </label>
                                    <p class="text-slate-600">
                                        {{ $ticket->approval->action_plan }}
                                    </p>
                                </div>

                                {{-- Catatan Approval --}}
                                @if ($ticket->approval->approval_note)
                                    <div class="md:col-span-2">
                                        <label class="block mb-1 text-sm font-semibold text-slate-700">
                                            Catatan Approval
                                        </label>
                                        <p class="text-slate-600">
                                            {{ $ticket->approval->approval_note }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-slate-600">Tiket belum diproses oleh tim IT.</p>

                            <div class="mt-6">
                                <a href="{{ route('helpdesk.index') }}"
                                    class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
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
