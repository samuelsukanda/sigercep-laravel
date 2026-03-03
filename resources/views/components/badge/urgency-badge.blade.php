@props(['urgency'])

@php
    $bgColor = match (trim(strtolower($urgency))) {
        'low' => '#10b981',       // hijau
        'medium' => '#eab308',    // kuning
        'high' => '#f97316',      // orange
        'critical' => '#ef4444',  // merah
        default => '#6b7280',     // abu
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
    {{ $urgency ?? '-' }}
</span>