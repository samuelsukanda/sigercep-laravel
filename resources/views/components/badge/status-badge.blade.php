@props(['status'])

@php
    $color = match ($status) {
        'Done' => 'text-emerald-500 font-semibold',
        'On Progress' => 'text-orange-500 font-semibold',
        'Pending' => 'text-gray-600 font-semibold',
        default => 'text-gray-500 font-semibold',
    };
@endphp

<span class="{{ $color }}">
    {{ $status ?? '-' }}
</span>
