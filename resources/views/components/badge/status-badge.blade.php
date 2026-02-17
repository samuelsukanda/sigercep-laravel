@props(['status'])

@php
    $color = match ($status) {
        'Done' => 'text-emerald-500 font-semibold',
        'Open' => 'text-blue-500 font-semibold',
        'On Progress' => 'text-orange-500 font-semibold',
        'In Progress' => 'text-orange-500 font-semibold',
        'Need Clarification' => 'text-orange-500 font-semibold',
        'Pending' => 'text-gray-600 font-semibold',
        'Closed' => 'text-gray-600 font-semibold',
        'Rejected' => 'text-red-600 font-semibold',
        'Sudah Di Kembalikan' => 'text-emerald-500 font-semibold',
        default => 'text-gray-500 font-semibold',
    };
@endphp

<span class="{{ $color }}">
    {{ $status ?? '-' }}
</span>
