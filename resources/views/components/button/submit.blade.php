@props([
    'type' => 'submit',
    'class' => '',
])

<button type="{{ $type }}"
    class="inline-block px-6 py-2 mb-0 text-xs font-semibold text-center text-white bg-gradient-to-tl from-emerald-500 to-teal-400 border-emerald-300 uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85 {{ $class }}">
    {{ $slot }}
</button>
