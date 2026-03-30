@props(['urgency'])

@php
    $value = trim(strtolower($urgency ?? ''));

    $bgColor = match ($value) {
        'low' => '#10b981',
        'medium' => '#eab308',
        'high' => '#f97316',
        'critical' => '#ef4444',
        default => null,
    };

    $isEmpty = !$urgency || $urgency === '-';
@endphp

<span style="
    {{ !$isEmpty ? "background-color: $bgColor; color:#fff;" : "color:#6b7280;" }}
    padding: 4px 12px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
">
    {{ $urgency ?? '-' }}
</span>