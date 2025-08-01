@props(['name', 'label', 'options' => [], 'selected' => [], 'required' => false])

@php
    $selected = is_array(old($name, $selected)) ? old($name, $selected) : [$selected];
@endphp

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700">
        {{ $label }} :
    </label>
    <select id="{{ $name }}" name="{{ $name }}[]" multiple
        {{ $attributes->merge(['class' => 'select2 w-full border border-gray-300 rounded-md px-3 py-2 text-sm text-gray-700 outline-none transition-all focus:ring-2 focus:ring-blue-500 focus:border-blue-500']) }}
        @if ($required) required @endif>
        @foreach ($options as $option)
            <option value="{{ $option }}" {{ in_array($option, $selected) ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
