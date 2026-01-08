@props([
    'label' => '',
    'name',
])

<div class="w-full">
    @if ($label)
        <label class="block text-sm font-semibold mb-2 text-slate-700">{{ $label }} :</label>
    @endif

    <label for="{{ $name }}-upload" class="block">
        <div
            class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-file-import text-gray-500 mr-2"></i>
                <span class="text-sm text-gray-700">Pilih File</span>
            </div>
            <span id="file-name" class="text-sm text-gray-500 truncate">No File Chosen</span>
        </div>
        <input id="{{ $name }}-upload" name="{{ $name }}" type="file" accept="application/pdf,.doc,.docx"
            class="hidden" />
    </label>

    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
