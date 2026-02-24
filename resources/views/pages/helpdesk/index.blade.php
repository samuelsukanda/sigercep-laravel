{{-- resources/views/tickets/index.blade.php --}}
@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700">Daftar Tiket Helpdesk</h6>
            <x-button.link href="{{ route('helpdesk.create') }}">
                Tambah Data
            </x-button.link>
        </div>

        @if (session('success'))
            <div
                class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                {{ session('success') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md rounded-lg px-2">
            <table id="datatable" data-date-column="3" class="min-w-full divide-y divide-gray-200 dark:divide-white-200">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100">
                    <tr>
                        <th class="px-6 py-3">No. Tiket</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Divisi</th>
                        <th class="px-6 py-3">Tanggal/Jam</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Tingkat Urgensi</th>
                        <th class="px-6 py-3">Status Tiket</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-s text-slate-500 bg-slate-100">
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td class="px-6 py-4 font-semibold">{{ $ticket->ticket_number }}</td>
                            <td class="px-6 py-4">
                                {{ ucfirst($ticket->user->name) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $ticket->unit }}
                            </td>
                            <td class="px-6 py-4" data-order="{{ \Carbon\Carbon::parse($ticket->created_at)->timestamp }}">
                                {{ \Carbon\Carbon::parse($ticket->created_at)->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="px-6 py-4">{{ $ticket->category }}</td>
                            <td class="px-6 py-4">
                                <x-badge.urgency-badge :urgency="$ticket->urgency" />
                            </td>
                            <td class="px-6 py-4">
                                <x-badge.status-badge :status="$ticket->status" />
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
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/alert-delete.js') }}"></script>

    <script>
        $(document).ready(function() {
            let table = $('#datatable').DataTable();
            table.order([1, 'desc']).draw();
        });
    </script>
@endpush
