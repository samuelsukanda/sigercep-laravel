@props([
    'label' => '',
    'name',
    'rows' => 3,
    'required' => false,
])

<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700">{{ $label }}</label>
    @endif

    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-700 placeholder-gray-500 focus:border-blue-500 focus:outline-none"
    >{{ old($name, $slot) }}</textarea>
</div>
