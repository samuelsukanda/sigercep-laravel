@props(['name', 'label', 'value' => '', 'placeholder' => '', 'id' => null, 'required' => false])

<div>
    <label for="{{ $id ?? $name }}" class="block text-sm font-semibold mb-2 text-slate-700">{{ $label }}:</label>
    <input 
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg text-gray-700 placeholder:text-gray-500 outline-none transition-all"
    />
</div>
