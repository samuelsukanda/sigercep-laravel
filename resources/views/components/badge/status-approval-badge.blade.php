@props(['status'])

@php
    $statusValue = trim(strtolower($status));

    $bgColor = match ($statusValue) {
        'pending' => '#4b5563',            // abu-abu
        'approved' => '#10b981',           // hijau
        'need clarification' => '#f97316', // orange
        'rejected' => '#ef4444',           // merah
        default => '#9ca3af',              // abu muda
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