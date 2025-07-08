@props([
    'href' => '#',
    'icon',
    'color' => 'emerald',
    'type' => 'link',
    'method' => null,
    'confirm' => null,
])

@if ($type === 'link')
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge(['class' => "text-{$color}-600 hover:underline text-sm"]) }}
        title="{{ $attributes['title'] ?? '' }}"
    >
        <i class="fa-solid fa-{{ $icon }}"></i>
    </a>
@else
    <form action="{{ $href }}" method="POST" class="inline delete-form">
        @csrf
        @method($method)
        <button 
            type="button"
            class="text-{{ $color }}-600 hover:underline text-sm delete-button"
            data-confirm="{{ $confirm }}"
            title="{{ $attributes['title'] ?? '' }}"
        >
            <i class="fa-solid fa-{{ $icon }}"></i>
        </button>
    </form>
@endif
