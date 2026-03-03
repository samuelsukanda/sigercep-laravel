@props(['status'])

@php
    $bgColor = match (trim(strtolower($status))) {
        'open' => '#3b82f6',        // biru
        'approved' => '#10b981',    // hijau
        'in progress' => '#f97316', // orange
        'closed' => '#6b7280',      // abu
        default => '#9ca3af',       // abu muda
    };
@endphp

<span style="
    background-color: {{ $bgColor }};
    color: #ffffff;
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
">
    {{ $status ?? '-' }}
</span>