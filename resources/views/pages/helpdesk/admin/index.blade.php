{{-- resources/views/admin/helpdesk/index.blade.php --}}
@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h6 class="text-xl font-bold text-slate-700 dark:text-white">Daftar Tiket Helpdesk</h6>
            {{-- @canAccess('helpdesk', 'create') --}}
            {{-- <x-button.link href="{{ route('reports.summary') }}">
                Laporan
            </x-button.link> --}}
            {{-- @endcanAccess --}}
        </div>

        {{-- FILTER --}}
        {{-- <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.helpdesk.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label>Status Tiket</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In
                                Progress
                            </option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Status Approval</label>
                        <select name="approval_status" class="form-select">
                            <option value="">Semua</option>
                            <option value="Approved" {{ request('approval_status') == 'Approved' ? 'selected' : '' }}>
                                Approved
                            </option>
                            <option value="Rejected" {{ request('approval_status') == 'Rejected' ? 'selected' : '' }}>
                                Rejected
                            </option>
                            <option value="Need Clarification"
                                {{ request('approval_status') == 'Need Clarification' ? 'selected' : '' }}>Need
                                Clarification
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Kategori</label>
                        <select name="category" class="form-select">
                            <option value="">Semua</option>
                            @foreach (['Hardware', 'Printer', 'Jaringan', 'Software', 'SIMRS'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                    {{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div> --}}

        {{-- TABLE --}}
        <div class="relative overflow-x-auto shadow-md rounded-lg px-2 dark:text-white">
            <table id="datatable" data-date-column="3"
                class="min-w-full divide-y divide-gray-200 dark:divide-white-200 dark:text-white">
                <thead class="text-xs text-slate-500 uppercase bg-slate-100 dark:text-white">
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
                                {{ $ticket->unit_name }}
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
                                <x-button.action href="{{ route('admin.helpdesk.show', $ticket->id) }}" icon="eye"
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
            table.order([3, 'desc']).draw();
        });
    </script>
@endpush
