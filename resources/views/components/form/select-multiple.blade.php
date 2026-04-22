@props(['name', 'label', 'options' => [], 'selected' => [], 'required' => false])

@php
    $selected = is_array(old($name, $selected)) ? old($name, $selected) : [$selected];
@endphp

<div class="select-multiple-wrapper">
    <label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700">
        {{ $label }} :
        <span class="text-xs text-blue-600 ml-2 count-selected">(0 dipilih)</span>
    </label>

    <select id="{{ $name }}" name="{{ $name }}[]" multiple
        {{ $attributes->merge(['class' => 'select2-multiple w-full']) }}
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