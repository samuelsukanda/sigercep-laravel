@props([
    'label' => '',
    'name' => 'files',
    'accept' => 'image/*',
    'maxSize' => 2,
    'maxFiles' => 10,
    'hint' => '',
    'badgeItems' => [],
])

@php
    $inputId = $name . '_multi_upload';
    $previewId = $name . '_preview';
    $countId = $name . '_count';
    $areaId = $name . '_area';
    $jsName = \Illuminate\Support\Str::camel($name) . 'MultiUpload';
@endphp

<div class="w-full" x-data="{{ $jsName }}()">

    {{-- Label --}}
    @if ($label)
        <label class="block text-sm font-semibold text-gray-700 mb-3">
            {{ $label }}
        </label>
    @endif

    {{-- Drop Zone Wrapper --}}
    <label for="{{ $inputId }}" class="block cursor-pointer group" @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false" @drop.prevent="onDrop($event)">
        <div :class="dragging
            ?
            'border-orange-400 bg-orange-50 shadow-md' :
            'border-gray-300'"
            class="relative border-2 border-dashed rounded-xl p-6 md:p-8
                transition-all duration-200 hover:border-orange-400
                hover:bg-orange-50 group-hover:shadow-md">

            {{-- Empty / Upload State --}}
            <div x-show="files.length === 0" class="text-center">
                <div
                    class="mx-auto w-20 h-20 mb-4 rounded-full
                        bg-gradient-to-br from-orange-100 to-orange-200
                        flex items-center justify-center transition-all duration-200
                        group-hover:from-orange-200 group-hover:to-orange-300
                        group-hover:scale-105">
                    <svg class="w-10 h-10 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                </div>

                <p class="text-base md:text-lg font-semibold text-gray-700 mb-2">
                    {{ $slot->isNotEmpty() ? $slot : 'Upload File' }}
                </p>

                <p class="text-sm text-gray-500 mb-1">
                    Klik untuk pilih file atau drag &amp; drop di sini
                </p>

                <p class="text-xs text-gray-400">
                    {{ strtoupper(str_replace('image/', '', $accept)) }}
                    (Maks. {{ $maxSize }}MB per file)
                </p>

                {{-- Badge Items --}}
                @if (count($badgeItems) > 0)
                    <div class="mt-6 flex flex-wrap justify-center gap-2">
                        @foreach ($badgeItems as $badge)
                            @php
                                $color = $badge['color'] ?? 'orange';
                                $colorMap = [
                                    'orange' => 'bg-orange-100 text-orange-700',
                                    'green' => 'bg-green-100 text-green-700',
                                    'blue' => 'bg-blue-100 text-blue-700',
                                    'red' => 'bg-red-100 text-red-700',
                                    'purple' => 'bg-purple-100 text-purple-700',
                                ];
                                $cls = $colorMap[$color] ?? $colorMap['orange'];
                            @endphp

                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $cls }}">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $badge['label'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Preview State --}}
            <div x-show="files.length > 0" x-cloak>

                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-gray-700">
                                File Berhasil Dipilih
                            </p>
                            <p class="text-xs text-gray-500" x-text="files.length + ' file dipilih'"></p>
                        </div>
                    </div>

                    <button type="button" @click.prevent="clearAll()"
                        onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.transform='scale(1)'"
                        onmouseout="this.style.background='#fef2f2'; this.style.color='#dc2626'; this.style.transform='scale(1)'"
                        style="
                            padding:6px 12px;
                            background:#fef2f2;
                            color:#dc2626;
                            font-size:12px;
                            font-weight:500;
                            border-radius:8px;
                            border:none;
                            cursor:pointer;
                            transition: all 0.3s ease;
                        ">
                        Hapus Semua
                    </button>
                </div>

                {{-- Grid Preview --}}
                <div class="space-y-2 max-h-72 overflow-y-auto">
                    <template x-for="(file, index) in files" :key="index">
                        <div
                            class="relative flex items-center justify-between px-3 py-2 rounded-lg border border-gray-200 bg-gray-50">

                            <div class="flex items-center overflow-hidden">
                                <div class="overflow-hidden">
                                    <p class="text-xs font-medium text-gray-700 truncate" x-text="file.name"></p>
                                    <p class="text-xs text-gray-400" x-text="file.sizeLabel"></p>
                                </div>
                            </div>

                            <button type="button" @click.prevent="removeFile(index)"
                                onmouseover="this.style.background='#dc2626'; this.style.transform='scale(1.15)'"
                                onmouseout="this.style.background='rgba(0,0,0,0.6)'; this.style.transform='scale(1)'"
                                style="
                                    width:22px;
                                    height:22px;
                                    border-radius:50%;
                                    border:none;
                                    background:rgba(0,0,0,0.6);
                                    color:white;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    cursor:pointer;
                                    font-size:11px;
                                    opacity:0;
                                    transition: all 0.25s ease;
                                "
                                x-init="$el.parentElement.addEventListener('mouseenter', () => $el.style.opacity = 1);
                                $el.parentElement.addEventListener('mouseleave', () => $el.style.opacity = 0);">
                                ✕
                            </button>

                        </div>
                    </template>
                </div>

                {{-- Add More --}}
                <label for="{{ $inputId }}"
                    onmouseover="this.style.background='rgba(118,100,228,0.15)'; this.style.color='#7664E4'; this.style.transform='scale(1)'"
                    onmouseout="this.style.background='#fff7ed'; this.style.color='#ea580c'; this.style.transform='scale(1)'"
                    style="
                        margin-top:16px;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        gap:8px;
                        padding:10px 16px;
                        background:#fff7ed;
                        color:#ea580c;
                        font-size:14px;
                        font-weight:500;
                        border-radius:8px;
                        cursor:pointer;
                        transition: all 0.25s ease;
                    ">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah File Lainnya
                </label>
            </div>

        </div>
    </label>

    {{-- Hidden actual input --}}
    <input type="file" id="{{ $inputId }}" name="{{ $name }}[]" accept="{{ $accept }}" multiple
        class="hidden" x-ref="fileInput" @change="onInputChange($event)" />

    {{-- Hint --}}
    @if ($hint)
        <p class="text-xs text-gray-500 mt-3 flex items-start gap-1">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                    clip-rule="evenodd" />
            </svg>
            <span>{{ $hint }}</span>
        </p>
    @endif

    {{-- Validation error --}}
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror

</div>


@once
    @push('scripts')
        <script>
            function {{ $jsName }}() {
                return {
                    files: [],
                    dragging: false,
                    maxSize: {{ $maxSize }},
                    maxFiles: {{ $maxFiles }},

                    onInputChange(event) {
                        this.addFiles(Array.from(event.target.files));
                    },

                    onDrop(event) {
                        this.dragging = false;
                        this.addFiles(Array.from(event.dataTransfer.files));
                    },

                    addFiles(newFiles) {
                        newFiles.forEach(file => {
                            if (this.files.length >= this.maxFiles) return;

                            const ext = file.name.split('.').pop().toLowerCase();
                            const isImage = file.type.startsWith('image/');
                            const sizeMB = file.size / 1024 / 1024;

                            if (sizeMB > this.maxSize) {
                                alert(`File ${file.name} melebihi ${this.maxSize}MB`);
                                return;
                            }

                            const entry = {
                                name: file.name,
                                ext: ext,
                                sizeLabel: sizeMB < 1 ?
                                    (file.size / 1024).toFixed(1) + ' KB' :
                                    sizeMB.toFixed(1) + ' MB',
                                isImage: isImage,
                                preview: null,
                                raw: file,
                            };

                            if (isImage) {
                                const reader = new FileReader();
                                reader.onload = e => {
                                    entry.preview = e.target.result;
                                };
                                reader.readAsDataURL(file);
                            }

                            this.files.push(entry);
                        });

                        this.syncInputFiles();
                    },

                    removeFile(index) {
                        this.files.splice(index, 1);
                        this.syncInputFiles();
                    },

                    clearAll() {
                        this.files = [];
                        this.syncInputFiles();
                    },

                    syncInputFiles() {
                        const dataTransfer = new DataTransfer();

                        this.files.forEach(file => {
                            dataTransfer.items.add(file.raw);
                        });

                        this.$refs.fileInput.files = dataTransfer.files;
                    }
                };
            }
        </script>
    @endpush
@endonce
