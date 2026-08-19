@extends('layouts.app')

@section('title', 'SIGERCEP - Dashboard')

@php
    $initialsOf = fn($name) => collect(explode(' ', trim((string) $name)))
        ->take(2)
        ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
        ->join('') ?:
    '?';
@endphp

@section('content')
    <div class="w-full px-6 py-6 mx-auto">

        {{-- Hero --}}
        <div class="dash-hero mb-8">
            <div class="dash-hero-chip">
                <i class="fas fa-hospital"></i>
            </div>
            <div>
                <p class="dash-hero-kicker">SIGERCEP &middot; Dashboard</p>
                <h3 class="dash-hero-title">Selamat datang, {{ auth()->user()->display_name }}</h3>
                <p class="dash-hero-sub">
                    {{ ucwords(auth()->user()->jabatan ?? '') }}
                    @if (auth()->user()->unit)
                        &middot; {{ ucwords(auth()->user()->unit) }}
                    @endif
                    &middot; {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        {{-- Rekap per unit --}}
        @foreach ($units as $unit)
            <div class="mb-8">
                <div class="dash-unit-title">
                    <div class="dash-unit-chip">
                        <i class="fas {{ $unit['icon'] }}"></i>
                    </div>
                    <span>{{ $unit['name'] }}</span>
                    <div class="dash-unit-line"></div>
                </div>

                <div class="flex flex-wrap -mx-3">
                    @foreach ($unit['modules'] as $module)
                        <div class="w-full max-w-full px-3 mb-6 sm:w-1/2 sm:flex-none xl:mb-6 xl:w-1/4 dash-module-col">
                            <a href="{{ route($module['route']) }}" class="dash-module-card">
                                <div class="dash-module-top">
                                    <span class="dash-module-icon">
                                        <i class="fas {{ $module['icon'] }}"></i>
                                    </span>
                                    <span class="dash-module-label">{{ $module['label'] }}</span>
                                </div>
                                <div>
                                    <p class="dash-module-count">{{ number_format($module['count']) }}</p>
                                    <p class="dash-module-last">
                                        <span class="dash-module-last-dot"></span>{{ $module['last'] }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        {{-- Grafik tren --}}
        @if (count($chartUnits))
            <div class="mb-8">
                <div class="dash-panel">
                    <div class="dash-panel-head">
                        <div>
                            <h5 class="dash-panel-title">Tren Aktivitas 6 Bulan</h5>
                            <p class="dash-panel-sub">Jumlah entri per unit</p>
                        </div>
                    </div>
                    <div class="dash-chart-wrap">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        @endif

        {{-- Aktivitas terbaru --}}
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mb-6 dash-col">
                <div class="dash-panel">
                    <div class="dash-panel-head">
                        <div>
                            <h5 class="dash-panel-title">Tiket Terbaru</h5>
                            <p class="dash-panel-sub">Helpdesk IT</p>
                        </div>
                        <a href="{{ $ticketIndexRoute }}" class="dash-panel-link">Lihat semua</a>
                    </div>
                    <ul class="dash-list">
                        @forelse ($recentTickets as $ticket)
                            <li>
                                <a href="{{ $ticketShowRoute($ticket->id) }}" class="dash-list-item">
                                    <span class="dash-avatar">{{ $initialsOf($ticket->user?->display_name) }}</span>
                                    <div class="dash-list-main">
                                        <span class="dash-list-title">{{ $ticket->ticket_number }}</span>
                                        <span class="dash-list-sub">{{ $ticket->category }} &middot;
                                            {{ $ticket->user?->display_name }}</span>
                                        <span class="dash-list-sub">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                    <x-badge.status-badge :status="$ticket->status" />
                                </a>
                            </li>
                        @empty
                            <li class="dash-list-empty">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="w-full max-w-full px-3 mb-6 dash-col">
                <div class="dash-panel">
                    <div class="dash-panel-head">
                        <div>
                            <h5 class="dash-panel-title">Komplain Terbaru</h5>
                            <p class="dash-panel-sub">Komplain IPSRS</p>
                        </div>
                        <a href="{{ route('komplain.ipsrs.index') }}" class="dash-panel-link">Lihat semua</a>
                    </div>
                    <ul class="dash-list">
                        @forelse ($recentKomplain as $komplain)
                            <li>
                                <a href="{{ route('komplain.ipsrs.show', $komplain->id) }}" class="dash-list-item">
                                    <span class="dash-avatar">{{ $initialsOf($komplain->nama) }}</span>
                                    <div class="dash-list-main">
                                        <span class="dash-list-title">{{ $komplain->nama }}</span>
                                        <span class="dash-list-sub">{{ $komplain->unit }} &middot;
                                            {{ \Carbon\Carbon::parse($komplain->tanggal)->translatedFormat('d F Y') }}</span>
                                        @if ($komplain->kendala)
                                            <span
                                                class="dash-list-sub">{{ \Illuminate\Support\Str::limit($komplain->kendala, 40) }}</span>
                                        @endif
                                    </div>
                                    <x-badge.status-badge :status="$komplain->status" />
                                </a>
                            </li>
                        @empty
                            <li class="dash-list-empty">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="w-full max-w-full px-3 mb-6 dash-col">
                <div class="dash-panel">
                    <div class="dash-panel-head">
                        <div>
                            <h5 class="dash-panel-title">Reservasi Terbaru</h5>
                            <p class="dash-panel-sub">Reservasi Ruangan</p>
                        </div>
                        <a href="{{ route('reservasi.ruangan.index') }}" class="dash-panel-link">Lihat semua</a>
                    </div>
                    <ul class="dash-list">
                        @forelse ($recentReservasi as $reservasi)
                            <li>
                                <a href="{{ route('reservasi.ruangan.show', $reservasi->id) }}" class="dash-list-item">
                                    <span class="dash-avatar">{{ $initialsOf($reservasi->nama) }}</span>
                                    <div class="dash-list-main">
                                        <span class="dash-list-title">{{ $reservasi->nama }}</span>
                                        <span class="dash-list-sub">{{ $reservasi->ruang }} &middot;
                                            {{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('d F Y') }}</span>
                                        <span class="dash-list-sub">{{ $reservasi->jam_mulai }} -
                                            {{ $reservasi->jam_selesai }}</span>
                                    </div>
                                    <x-badge.status-badge :status="$reservasi->approval" />
                                </a>
                            </li>
                        @empty
                            <li class="dash-list-empty">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById("trendChart");
            if (!canvas || typeof Chart === "undefined") return;

            const chart = @json(['labels' => $chartLabels, 'units' => $chartUnits]);

            new Chart(canvas, {
                type: "line",
                data: {
                    labels: chart.labels,
                    datasets: chart.units.map(function(u) {
                        return {
                            label: u.name,
                            data: u.data,
                            borderColor: u.color,
                            backgroundColor: u.color + "22",
                            tension: 0.35,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: "#ffffff",
                            pointBorderColor: u.color,
                            pointBorderWidth: 2,
                            borderWidth: 2.5,
                        };
                    }),
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: "index",
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: "bottom",
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 16,
                                font: {
                                    family: "Plus Jakarta Sans",
                                    size: 12
                                },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: {
                                    family: "Plus Jakarta Sans"
                                }
                            },
                            grid: {
                                color: "#f1f5f9"
                            },
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: "Plus Jakarta Sans"
                                }
                            },
                            grid: {
                                display: false
                            },
                        },
                    },
                },
            });
        });
    </script>
@endpush
