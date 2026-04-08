@props(['name', 'label', 'rows' => 3, 'readonly' => false])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-white">
        {{ $label }} :
    </label>

    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->merge([
            'class' =>
                'focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all placeholder:text-gray-500 focus:outline-none focus:border-blue-500 ' .
                ($readonly ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : ''),
        ]) }}>{{ $slot }}</textarea>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
