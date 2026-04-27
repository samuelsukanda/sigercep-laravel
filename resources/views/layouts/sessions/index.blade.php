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
                        <svg fill="none" stroke="#f97316" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
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
                        $diff = now()->diffInMinutes(\Carbon\Carbon::createFromTimestamp($s->last_activity));
                        $parity = $index % 2 === 0 ? 'um-row-card--even' : 'um-row-card--odd';

                        $timeClass =
                            $diff < 5
                                ? 'um-time-rel--fresh'
                                : ($diff < 30
                                    ? 'um-time-rel--recent'
                                    : 'um-time-rel--old');
                        $timeLabel =
                            $diff < 1
                                ? 'Baru saja'
                                : ($diff < 60
                                    ? $diff . ' menit lalu'
                                    : floor($diff / 60) . ' jam lalu');
                        $statusClass =
                            $diff < 5 ? 'um-status--online' : ($diff < 30 ? 'um-status--idle' : 'um-status--away');
                        $statusLabel = $diff < 5 ? 'Online' : ($diff < 30 ? 'Idle' : 'Away');
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
                                    <span class="um-device um-device--mobile">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="5" y="2" width="14" height="20" rx="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18"
                                                stroke-linecap="round" />
                                        </svg>
                                        Mobile
                                    </span>
                                @elseif($dev === 'tablet')
                                    <span class="um-device um-device--tablet">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="4" y="2" width="16" height="20" rx="2" />
                                            <line x1="12" y1="18" x2="12.01" y2="18"
                                                stroke-linecap="round" />
                                        </svg>
                                        Tablet
                                    </span>
                                @else
                                    <span class="um-device um-device--desktop">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <rect x="2" y="3" width="20" height="14" rx="2" />
                                            <line x1="8" y1="21" x2="16" y2="21" />
                                            <line x1="12" y1="17" x2="12" y2="21" />
                                        </svg>
                                        Desktop
                                    </span>
                                @endif
                            </div>

                            <div>
                                <div class="um-time">{{ date('d/m/Y H:i:s', $s->last_activity) }}</div>
                                <div class="um-time-rel {{ $timeClass }}">{{ $timeLabel }}</div>
                            </div>

                        </div>

                        {{-- Row 2 --}}
                        <div class="um-row2">

                            <div></div>

                            <div class="um-detail">
                                <svg fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="um-detail-label">Login ID:</span>
                                <span class="um-detail-val">{{ $s->username ?? ($s->name ?? '-') }}</span>
                            </div>

                            <div class="um-detail">
                                <svg fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="2" y1="12" x2="22" y2="12" />
                                    <path
                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                </svg>
                                <span class="um-detail-label">Browser:</span>
                                <span class="um-detail-val">{{ $s->browser ?? '-' }}</span>
                            </div>

                            <div class="um-detail">
                                <svg fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="14" rx="2" />
                                    <line x1="2" y1="20" x2="22" y2="20" />
                                </svg>
                                <span class="um-detail-label">OS:</span>
                                <span class="um-detail-val">{{ $s->platform ?? '-' }}</span>
                            </div>

                            <div>
                                <span class="um-status {{ $statusClass }}">
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
