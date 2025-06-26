@props(['status'])

@php
    $color = match ($status) {
        'Pending' => 'text-gray-500 font-semibold',
        'Approved' => 'text-emerald-500 font-semibold',
        'Rejected' => 'text-red-600 font-semibold',
        default => null,
    };
@endphp

<span class="{{ $color }}">
    {{ $status ?? '-' }}
</span>
