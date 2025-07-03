@props(['name', 'label', 'options' => [], 'selected' => null, 'required' => false])

@php use Illuminate\Support\Str; @endphp

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 dark:text-white">
        {{ $label }} :
    </label>
    @foreach ($options as $value => $optionLabel)
        @php
            $id = $name . '-' . Str::slug($value);
        @endphp
        <div class="flex items-center gap-x-3">
            <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
                {{ $required ? 'required' : '' }} {{ $selected == $value ? 'checked' : '' }} class="form-radio">
            <label class="ml-1" for="{{ $id }}" class="cursor-pointer font-semibold">
                {{ $optionLabel }}
            </label>
        </div>
    @endforeach
</div>
