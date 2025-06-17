@props([
    'label' => '',
    'name',
    'preview' => null, // path preview atau null
])

<div class="w-full">
    @if($label)
        <label class="block text-sm font-semibold mb-2 text-slate-700">{{ $label }}</label>
    @endif

    <label for="{{ $name }}-upload" class="block">
        <div id="custom-upload"
            class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-file-import text-gray-500 mr-2"></i>
                <span class="text-sm text-gray-700">Pilih File</span>
            </div>
            <span id="file-name" class="text-sm text-gray-500 truncate"></span>
        </div>
        <input id="{{ $name }}-upload" name="{{ $name }}" type="file" class="hidden" />
    </label>

    @if ($preview)
        <img src="{{ asset('storage/' . $preview) }}" alt="Preview"
            class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-auto max-w-full" />
    @endif
</div>
