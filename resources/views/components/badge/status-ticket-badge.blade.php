@props(['status'])

@php
    $textColor = match (trim(strtolower($status))) {
        'open' => '#3b82f6', // biru
        'approved' => '#10b981', // hijau
        'in progress' => '#f97316', // orange
        'closed' => '#6b7280', // abu
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
