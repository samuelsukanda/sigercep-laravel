@props(['name', 'label', 'options' => [], 'selected' => null, 'required' => false])

@php
    use Illuminate\Support\Str;

    $isOtherSelected = !in_array($selected, array_keys($options));
@endphp

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-sm font-semibold text-slate-700 dark:text-white">
        {{ $label }} :
    </label>

    @foreach ($options as $value => $optionLabel)
        @php
            $id = $name . '-' . Str::slug($value);
            $isOther = strtolower($value) === 'other';
        @endphp

        <div class="flex items-center gap-x-3">
            <input type="radio" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"
                {{ $required ? 'required' : '' }}
                {{ $selected == $value || ($isOther && $isOtherSelected) ? 'checked' : '' }} class="form-radio"
                onchange="toggleInput_{{ $name }}()">

            <label for="{{ $id }}" class="ml-1 cursor-pointer">{{ $optionLabel }}</label>

            @if ($isOther)
                <input type="text" name="{{ $name }}_input" id="input-{{ $name }}"
                    class="form-input ml-2 border border-gray-300 rounded px-2 py-1 text-sm"
                    placeholder="Masukkan pilihan lain..." value="{{ $isOtherSelected ? $selected : '' }}"
                    style="{{ $isOtherSelected ? '' : 'display: none;' }}">
            @endif
        </div>
    @endforeach
</div>

@once
    <script src="{{ asset('js/components/radio-with-input.js') }}"></script>
@endonce
<script>
    function toggleInput_{{ $name }}() {
        const radios = document.querySelectorAll('input[name="{{ $name }}"]');
        const input = document.getElementById("input-{{ $name }}");

        if (!input) return;

        let show = false;
        radios.forEach(radio => {
            if (radio.checked && radio.value.toLowerCase() === "other") {
                show = true;
            }
        });

        input.style.display = show ? "inline-block" : "none";
        input.required = show;
        if (!show) input.value = "";
    }

    document.addEventListener("DOMContentLoaded", function() {
        toggleInput_{{ $name }}();
    });
</script>
