@props(['name', 'label', 'value' => '', 'type' => 'text'])

<label for="{{ $name }}" class="block text-sm font-semibold mb-2 text-slate-700 dark:text-white">
    {{ $label }} :
</label>
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
    {{ $attributes->merge(['class' => 'focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none']) }}>

@error($name)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
