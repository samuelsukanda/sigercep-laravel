<button 
    type="{{ $type ?? 'submit' }}"
    class="inline-block px-6 py-2 mb-0 text-xs font-semibold text-center text-slate-700 uppercase align-middle transition-all rounded-lg shadow-md hover:shadow-xs active:opacity-85 {{ $class ?? '' }}">
    {{ $slot }}
</button>
