@props([
    'href' => '#',
    'icon',
    'color' => 'emerald',
    'type' => 'link', // 'link' or 'button'
    'method' => null,
    'confirm' => null,
])

@if ($type === 'link')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "text-{$color}-600 hover:underline text-sm"]) }}>
        <i class="fa-solid fa-{{ $icon }}"></i>
    </a>
@else
    <form action="{{ $href }}" method="POST" class="inline">
        @csrf
        @method($method)
        <button type="submit" @if ($confirm) onclick="return confirm('{{ $confirm }}')" @endif
            class="text-{{ $color }}-600 hover:underline text-sm">
            <i class="fa-solid fa-{{ $icon }}"></i>
        </button>
    </form>
@endif
