@props(['status'])

@php
    $statusValue = trim(strtolower($status));

    $textColor = match ($statusValue) {
        'pending' => '#4b5563', // abu-abu
        'approved' => '#10b981', // hijau
        'need clarification' => '#f97316', // orange
        'rejected' => '#ef4444', // merah
        default => '#9ca3af', // abu muda
    };
@endphp

<span style="
    color: {{ $textColor }};
    font-size: 12px;
    font-weight: bold;
">
    {{ strtoupper($status ?? '-') }}
</span>
