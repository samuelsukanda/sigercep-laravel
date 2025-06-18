@props(['name', 'label', 'options' => [], 'selected' => '', 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700">{{ $label }} :</label>
    <select id="{{ $name }}" name="{{ $name }}"
        class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all"
        @if ($required) required @endif>
        <option disabled {{ $selected == '' ? 'selected' : '' }}>Pilih {{ $label }}</option>
        @foreach ($options as $option)
            <option value="{{ $option }}" {{ $selected == $option ? 'selected' : '' }}>{{ $option }}</option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
