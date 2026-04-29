@extends('layouts.app')

@section('title', 'SIGERCEP - Daftar User')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/users.css') }}">
@endpush

@section('content')
    <div class="um-wrap">

        {{-- HEADER --}}
        <div class="um-header-card">
            <div class="um-title-row">
                <div class="um-title-left">
                    <div class="um-icon-box">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <h1 class="um-title-text">Daftar User</h1>
                        <p class="um-title-sub">Manajemen pengguna sistem</p>
                    </div>
                </div>

                <div class="um-stat um-stat--purple">
                    <div class="um-stat-label">Total User</div>
                    <div class="um-stat-value">{{ count($users) }}</div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="um-table-card">

            <div class="relative overflow-x-auto px-2">
                <table id="userTable" class="datatable-custom w-full">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>NIK</th>
                            <th>Username / Email</th>
                            <th>Status Karyawan</th>
                            <th class="text-right">Info</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $avatarClasses = [
                                'um-avatar--teal',
                                'um-avatar--blue',
                                'um-avatar--purple',
                                'um-avatar--pink',
                                'um-avatar--amber',
                            ];
                        @endphp

                        @forelse($users as $u)
                            @php
                                $nama = ucwords(str_replace('.', ' ', $u->name ?? 'User'));
                                $initials = collect(explode(' ', $nama))
                                    ->take(2)
                                    ->map(fn($w) => strtoupper($w[0]))
                                    ->join('');

                                $avClass = $avatarClasses[abs(crc32($nama)) % count($avatarClasses)];
                            @endphp

                            <tr>
                                {{-- NAMA --}}
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="um-avatar {{ $avClass }}" style="margin-right: 5px;">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="um-name">{{ $nama }}</div>
                                            <div class="um-jabatan">
                                                {{ $u->jabatan ?? 'Tidak ada jabatan' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                 {{-- NIK --}}
                                <td>
                                    <span class="um-name">
                                        {{ $u->nik ?? '-' }}
                                    </span>
                                </td>

                                {{-- USERNAME --}}
                                <td>
                                    <span class="um-username-pill">
                                        {{ $u->username ?? '-' }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="um-status um-status--active">
                                        <span class="um-status-dot"></span>
                                         {{ $u->status_karyawan ?? '-' }}
                                    </span>
                                </td>

                                {{-- INFO --}}
                                <td class="text-right">
                                    <div class="um-meta">
                                        <span class="um-meta-label">ID:</span>
                                        <span class="um-meta-val">#{{ $u->id }}</span>
                                    </div>
                                    <div class="um-meta">
                                        <span class="um-meta-label">Dibuat:</span>
                                        <span class="um-meta-val">
                                            {{ $u->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10">
                                    <div class="um-empty">
                                        <i class="fa-solid fa-users-slash"></i>
                                        <p>Belum ada data pengguna</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-users.js') }}"></script>
@endpush
