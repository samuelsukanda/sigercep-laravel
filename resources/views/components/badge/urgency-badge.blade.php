@props(['urgency'])

@php
    $value = trim(strtolower($urgency ?? ''));

    $bgColor = match ($value) {
        'low' => '#22c55e',
        'medium' => '#eab308',
        'high' => '#f97316',
        'critical' => '#ef4444',
        default => null,
    };

    $isEmpty = !$urgency || $urgency === '-';
@endphp

<span class="urgency-badge" style="
    {{ !$isEmpty ? "background-color: $bgColor;" : "" }}
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
">
    <span style="color: #fff !important;">{{ $urgency ?? '-' }}</span>
</span>