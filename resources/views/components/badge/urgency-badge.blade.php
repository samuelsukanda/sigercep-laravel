@props(['urgency'])

@php
    $color = match ($urgency) {
        'Low' => 'text-emerald-500 font-semibold',
        'Medium' => 'text-orange-500 font-semibold',
        'High' => 'text-red font-semibold',
        'Critical' => 'text-dark font-semibold',
        default => 'text-gray-500 font-semibold',
    };
@endphp

<span class="{{ $color }}">
    {{ $urgency ?? '-' }}
</span>
