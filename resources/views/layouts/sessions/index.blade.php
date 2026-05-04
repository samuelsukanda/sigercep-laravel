@extends('layouts.app')

@section('title', 'SIGERCEP - User Monitoring')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/monitoring.css') }}">
@endpush

@section('content')
    <div class="um-wrap">

        {{-- ── Header Card ── --}}
        <div class="um-header-card">
            <div class="um-title-row">
                <div class="um-title-left">
                    <div class="um-icon-box">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <div>
                        <div class="um-title-text">User Monitoring</div>
                        <div class="um-title-sub">Sesi aktif pengguna sistem</div>
                    </div>
                </div>
                <div class="um-live-badge">
                    <span class="um-pulse"></span>
                    LIVE
                </div>
            </div>

            <div class="um-stats">
                <div class="um-stat um-stat--orange">
                    <div class="um-stat-label">Total Sesi</div>
                    <div class="um-stat-value">{{ count($sessions) }}</div>
                </div>
                <div class="um-stat um-stat--teal">
                    <div class="um-stat-label">User Unik</div>
                    <div class="um-stat-value">
                        {{ collect($sessions)->whereNotNull('user_id')->unique('user_id')->count() }}
                    </div>
                </div>
                <div class="um-stat um-stat--blue">
                    <div class="um-stat-label">Desktop</div>
                    <div class="um-stat-value">
                        {{ collect($sessions)->filter(fn($s) => strtolower($s->device ?? '') === 'desktop')->count() }}
                    </div>
                </div>
                <div class="um-stat um-stat--purple">
                    <div class="um-stat-label">Mobile</div>
                    <div class="um-stat-value">
                        {{ collect($sessions)->filter(fn($s) => strtolower($s->device ?? '') === 'mobile')->count() }}
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Table Card ── --}}
        <div class="um-table-card">

            <div class="um-thead">
                <div>No</div>
                <div>Nama User</div>
                <div>IP Address</div>
                <div>Device</div>
                <div>Last Activity</div>
            </div>

            <div class="um-rows">
                @php
                    $avatarClasses = [
                        'um-avatar--teal',
                        'um-avatar--blue',
                        'um-avatar--purple',
                        'um-avatar--pink',
                        'um-avatar--amber',
                    ];
                @endphp

                @forelse($sessions as $index => $s)
                    @php
                        $nama = ucwords(str_replace('.', ' ', $s->name ?? 'Guest'));
                        $initials = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                        $avClass = $avatarClasses[abs(crc32($nama)) % count($avatarClasses)];
                        $dev = strtolower($s->device ?? 'desktop');
                        $parity = $index % 2 === 0 ? 'um-row-card--even' : 'um-row-card--odd';

                        $statusClass = match ($s->status) {
                            'online' => 'um-status--online',
                            'idle' => 'um-status--idle',
                            default => 'um-status--away',
                        };

                        $statusLabel = match ($s->status) {
                            'online' => 'Online',
                            'idle' => 'Idle',
                            default => 'Away',
                        };

                        $timeClass = match ($s->status) {
                            'online' => 'um-time-rel--fresh',
                            'idle' => 'um-time-rel--recent',
                            default => 'um-time-rel--old',
                        };
                    @endphp

                    <div class="um-row-card {{ $parity }}">

                        {{-- Row 1 --}}
                        <div class="um-row1">

                            <div>
                                <span class="um-uid um-uid--id">{{ $loop->iteration }}</span>
                            </div>

                            <div class="um-name-cell">
                                <div class="um-avatar {{ $avClass }}">{{ $initials }}</div>
                                <div>
                                    <div class="um-name">{{ $nama }}</div>
                                    <div style="font-size:11px; color:#9ca3af;">
                                        {{ $s->jabatan ? ucwords($s->jabatan) : '-' }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <span class="um-ip">{{ $s->ip_address }}</span>
                            </div>

                            <div>
                                @if ($dev === 'mobile')
                                    <span class="um-device um-device--mobile">Mobile</span>
                                @elseif($dev === 'tablet')
                                    <span class="um-device um-device--tablet">Tablet</span>
                                @else
                                    <span class="um-device um-device--desktop">Desktop</span>
                                @endif
                            </div>

                            <div>
                                <div class="um-time">
                                    {{ $s->last_activity_at ?? '-' }}
                                </div>

                                <div class="um-time-rel" id="time-{{ $s->id }}">
                                    {{ $s->time_label ?? '-' }}
                                </div>
                            </div>

                        </div>

                        {{-- Row 2 --}}
                        <div class="um-row2">

                            <div></div>

                            <div class="um-detail">
                                <span class="um-detail-label">Login ID:</span>
                                <span class="um-detail-val">
                                    {{ $s->user_id ?? '-' }}
                                </span>
                            </div>

                            <div class="um-detail">
                                <span class="um-detail-label">Browser:</span>
                                <span class="um-detail-val">{{ $s->browser ?? '-' }}</span>
                            </div>

                            <div class="um-detail">
                                <span class="um-detail-label">OS:</span>
                                <span class="um-detail-val">{{ $s->platform ?? '-' }}</span>
                            </div>

                            <div>
                                <span class="um-status {{ $statusClass }}" id="status-{{ $s->id }}">
                                    <span class="um-status-dot"></span>
                                    {{ $statusLabel }}
                                </span>
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="um-empty">
                        <svg fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p>Tidak ada sesi aktif saat ini</p>
                    </div>
                @endforelse
            </div>

            @if (count($sessions) > 0)
                <div class="um-footer">
                    <span>Menampilkan <strong>{{ count($sessions) }}</strong> sesi aktif</span>
                    <button class="um-refresh-btn" onclick="window.location.reload()">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>
                </div>
            @endif

        </div>
    </div>
@endsection
