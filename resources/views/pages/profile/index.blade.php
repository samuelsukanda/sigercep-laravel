@extends('layouts.app')

@section('title', 'SIGERCEP - Profil')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <x-page-header icon="fa-user-circle" title="Profil Saya" subtitle="Informasi akun Anda" />

                <div class="dash-panel">
                    <div class="dash-panel-head">
                        <div>
                            <div class="dash-panel-title">Data Akun</div>
                            <div class="dash-panel-sub">Data ini dikelola oleh administrator</div>
                        </div>
                        <div class="dash-avatar" style="width: 3.5rem; height: 3.5rem; font-size: 1.25rem;">
                            {{ strtoupper(substr($user->display_name, 0, 1)) }}
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        @foreach ([
                            'Nama' => $user->display_name,
                            'Username' => strstr($user->username, '@', true) ?: $user->username,
                            'Email' => $user->email,
                            'NIK' => $user->nik,
                            'Unit' => $user->unit,
                            'Jabatan' => $user->jabatan,
                            'Status Karyawan' => $user->status_karyawan,
                            'Terakhir Online' => optional($user->last_seen_at)->diffForHumans(),
                        ] as $label => $value)
                            <div style="display: flex; align-items: center; padding: 0.75rem 0.5rem; border-bottom: 1px solid #f1f5f9;">
                                <div style="flex: 1; font-size: 0.85rem; font-weight: 600; color: #64748b;">{{ $label }}</div>
                                <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-strong);">{{ $value ?: '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection