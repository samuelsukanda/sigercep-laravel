@props(['href', 'color' => 'slate'])

<a href="{{ $href }}"
    {{ $attributes->merge([
        'class' => "inline-block text-sm px-6 py-2 rounded-lg shadow-md border border-transparent text-slate-700 bg-{$color}-100 hover:shadow-lg transition",
    ]) }}>
    {{ $slot }}
</a>
