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
                    <div class="um-stat-value">{{ $userCount }}</div>
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
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script src="{{ asset('assets/js/datatable/datatable-users.js') }}"></script>
@endpush
