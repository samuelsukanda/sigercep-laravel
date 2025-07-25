@props([
    'href',
    'class' => '',
])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => "inline-block text-sm px-6 py-2 rounded-lg shadow-md border border-cyan-200 text-white bg-gradient-to-tl from-blue-700 to-cyan-500 hover:shadow-lg transition {$class}",
    ]) }}>
    {{ $slot }}
</a>
