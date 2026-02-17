{{-- resources/views/reports/summary.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Laporan Helpdesk</h2>
        <a href="{{ route('admin.helpdesk.index') }}" class="btn btn-secondary">← Kembali ke Tiket</a>
    </div>

    {{-- Filter Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reports.summary') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Periode Dari</label>
                    <input type="date" name="periode_dari" class="form-control" value="{{ request('periode_dari', now()->startOfMonth()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Periode Sampai</label>
                    <input type="date" name="periode_sampai" class="form-control" value="{{ request('periode_sampai', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua</option>
                        <option value="Hardware" {{ request('kategori')=='Hardware' ? 'selected':'' }}>Hardware</option>
                        <option value="Printer" {{ request('kategori')=='Printer' ? 'selected':'' }}>Printer</option>
                        <option value="Jaringan" {{ request('kategori')=='Jaringan' ? 'selected':'' }}>Jaringan</option>
                        <option value="Software" {{ request('kategori')=='Software' ? 'selected':'' }}>Software</option>
                        <option value="SIMRS" {{ request('kategori')=='SIMRS' ? 'selected':'' }}>SIMRS</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status Tiket</label>
                    <select name="status_tiket" class="form-select">
                        <option value="">Semua</option>
                        <option value="Open" {{ request('status_tiket')=='Open' ? 'selected':'' }}>Open</option>
                        <option value="In Progress" {{ request('status_tiket')=='In Progress' ? 'selected':'' }}>In Progress</option>
                        <option value="Closed" {{ request('status_tiket')=='Closed' ? 'selected':'' }}>Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status Approval</label>
                    <select name="status_approval" class="form-select">
                        <option value="">Semua</option>
                        <option value="Approved" {{ request('status_approval')=='Approved' ? 'selected':'' }}>Approved</option>
                        <option value="Rejected" {{ request('status_approval')=='Rejected' ? 'selected':'' }}>Rejected</option>
                        <option value="Need Clarification" {{ request('status_approval')=='Need Clarification' ? 'selected':'' }}>Need Clarification</option>
                    </select>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <a href="{{ route('reports.summary') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </a>
                    <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success px-4">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Angka --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Total Tiket Masuk</h6>
                    <h2 class="mb-0">{{ $totalTickets }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Approved</h6>
                    <h2 class="mb-0">{{ $totalApproved }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Rejected</h6>
                    <h2 class="mb-0">{{ $totalRejected }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h6 class="card-title">Need Clarification</h6>
                    <h2 class="mb-0">{{ $totalNeedClarification }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Tiket Open</h6>
                    <h2 class="mb-0">{{ $totalOpen }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">In Progress</h6>
                    <h2 class="mb-0">{{ $totalInProgress }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Closed</h6>
                    <h2 class="mb-0">{{ $totalClosed }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-dark text-white shadow">
                <div class="card-body">
                    <h6 class="card-title">Rata-rata Waktu Penyelesaian</h6>
                    <h2 class="mb-0">{{ number_format($avgResolution, 2) }} Hari</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Laporan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Detail Tiket (Periode {{ request('periode_dari', now()->startOfMonth()->format('d-m-Y')) }} s/d {{ request('periode_sampai', now()->format('d-m-Y')) }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>No. Tiket</th>
                            <th>Tanggal/Jam</th>
                            <th>Nama Pelapor</th>
                            <th>Divisi</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Status Tiket</th>
                            <th>Status Approval</th>
                            <th>Approved By</th>
                            <th>Estimasi Penyelesaian</th>
                            <th>Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $index => $ticket)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $ticket->ticket_number }}</strong></td>
                            <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>{{ $ticket->unit }}</td>
                            <td>{{ $ticket->category }}</td>
                            <td>{{ \Str::limit($ticket->description, 50) }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->status == 'Open' ? 'info' : ($ticket->status == 'In Progress' ? 'primary' : 'secondary') }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>
                                @if($ticket->approval)
                                    <span class="badge bg-{{ $ticket->approval->approval_status == 'Approved' ? 'success' : ($ticket->approval->approval_status == 'Rejected' ? 'danger' : 'warning') }}">
                                        {{ $ticket->approval->approval_status }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>{{ $ticket->approval->approved_by ?? '-' }}</td>
                            <td>{{ $ticket->approval ? $ticket->approval->estimated_completion->format('d-m-Y H:i') : '-' }}</td>
                            <td>{{ $ticket->resolved_at ? $ticket->resolved_at->format('d-m-Y') : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">Tidak ada data tiket pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Rekap per Kategori --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Rekap Berdasarkan Kategori</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        @foreach($categoryRecap as $category => $count)
                        <tr>
                            <td>{{ $category }}</td>
                            <td class="text-end fw-bold">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">Rekap Berdasarkan Status</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        @foreach($statusRecap as $status => $count)
                        <tr>
                            <td>{{ $status }}</td>
                            <td class="text-end fw-bold">{{ $count }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection