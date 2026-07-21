@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Knowledge Base')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl flex justify-between items-center">
                        <h6 class="mb-0 font-bold text-lg">Edit Artikel Knowledge Base</h6>
                    </div>

                    <div class="px-6 mt-4">
                        <div class="p-3 text-sm rounded-lg bg-slate-50 border border-slate-200 text-slate-600 flex flex-wrap"
                            style="gap: 16px;">
                            <div class="flex items-center font-semibold" style="gap: 6px;">
                                <i class="fas fa-eye text-slate-400" style="width: 14px;"></i>
                                <span>{{ number_format($knowledgeBase->views) }} views</span>
                            </div>
                            <div class="flex items-center font-semibold" style="gap: 6px;">
                                <i class="fas fa-user text-slate-400" style="width: 14px;"></i>
                                <span>{{ ucwords(str_replace('.', ' ', $knowledgeBase->author?->name ?? 'Unknown')) }}</span>
                            </div>
                            <div class="flex items-center font-semibold" style="gap: 6px;">
                                <i class="fas fa-clock text-slate-400" style="width: 14px;"></i>
                                <span>Terakhir diubah {{ $knowledgeBase->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6">
                        <form action="{{ route('knowledge-base.update', $knowledgeBase) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <x-form.input name="title" label="Judul Artikel" :value="$knowledgeBase->title" required />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">Kategori</label>
                                    <select name="category"
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all placeholder:text-gray-500 focus:outline-none focus:border-blue-500">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}"
                                                {{ old('category', $knowledgeBase->category) == $cat ? 'selected' : '' }}>
                                                {{ $cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">Status <span
                                            class="text-red-500">*</span></label>
                                    <select name="status"
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all placeholder:text-gray-500 focus:outline-none focus:border-blue-500"
                                        required>
                                        <option value="published"
                                            {{ old('status', $knowledgeBase->status) == 'published' ? 'selected' : '' }}>
                                            Published</option>
                                        <option value="draft"
                                            {{ old('status', $knowledgeBase->status) == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <x-form.textarea name="content" label="Konten Artikel" :value="$knowledgeBase->content" required />
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Upload Foto Baru
                                    (Opsional)</label>
                                <label for="photo-upload" class="block">
                                    <div
                                        class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-2">
                                            <i class="fa-solid fa-file-import text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-700">Pilih Foto</span>
                                        </div>
                                        <span id="photo-file-name" class="text-sm text-gray-500 truncate">No File
                                            Chosen</span>
                                    </div>
                                    <input id="photo-upload" name="photo" type="file" accept="image/*" class="hidden"
                                        onchange="document.getElementById('photo-file-name').textContent = this.files[0] ? this.files[0].name : 'No File Chosen'" />
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Maks. 5MB (JPG, PNG)</p>
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($knowledgeBase->photo_path)
                                    <div class="mt-3">
                                        <p class="text-xs text-slate-500 font-semibold mb-2">Foto Saat Ini:</p>
                                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer"
                                            style="width: 280px; flex-shrink: 0;"
                                            onclick="openMediaModal('{{ asset('storage/' . $knowledgeBase->photo_path) }}', 'image')">
                                            <div
                                                style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #f8fafc;">
                                                <img src="{{ asset('storage/' . $knowledgeBase->photo_path) }}"
                                                    alt="Foto Saat Ini" class="w-full h-full object-cover">
                                                <div style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.2); opacity: 0; transition: opacity 0.2s;"
                                                    onmouseover="this.style.opacity='1'"
                                                    onmouseout="this.style.opacity='0'">
                                                    <i class="fas fa-expand text-lg text-white"></i>
                                                </div>
                                            </div>
                                            <div
                                                class="p-3 text-xs text-gray-500 font-semibold border-t flex items-center justify-between">
                                                <span><i class="fas fa-image mr-1 text-blue-500"></i> Foto Lampiran</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Upload Video Baru
                                    (Opsional)</label>
                                <label for="video-upload" class="block">
                                    <div
                                        class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-2">
                                            <i class="fa-solid fa-file-import text-gray-500 mr-2"></i>
                                            <span class="text-sm text-gray-700">Pilih Video</span>
                                        </div>
                                        <span id="video-file-name" class="text-sm text-gray-500 truncate">No File
                                            Chosen</span>
                                    </div>
                                    <input id="video-upload" name="video" type="file" accept="video/*" class="hidden"
                                        onchange="document.getElementById('video-file-name').textContent = this.files[0] ? this.files[0].name : 'No File Chosen'" />
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Maks. 20MB (MP4, MKV, AVI, WEBM)</p>
                                @error('video')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($knowledgeBase->video_path)
                                    <div class="mt-3">
                                        <p class="text-xs text-slate-500 font-semibold mb-2">Video Saat Ini:</p>
                                        @if (Str::contains($knowledgeBase->video_path, ['youtube.com', 'youtu.be']))
                                            @php
                                                $editVideoUrl = $knowledgeBase->video_path;
                                                if (str_contains($editVideoUrl, 'youtube.com/watch?v=')) {
                                                    $editVideoUrl = str_replace('watch?v=', 'embed/', $editVideoUrl);
                                                    if (str_contains($editVideoUrl, '&')) {
                                                        $editVideoUrl = substr(
                                                            $editVideoUrl,
                                                            0,
                                                            strpos($editVideoUrl, '&'),
                                                        );
                                                    }
                                                } elseif (str_contains($editVideoUrl, 'youtu.be/')) {
                                                    $editVideoUrl = str_replace(
                                                        'youtu.be/',
                                                        'youtube.com/embed/',
                                                        $editVideoUrl,
                                                    );
                                                }
                                                preg_match(
                                                    '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+\?v=)|youtu\.be/)([^"&?/\s]{11})%i',
                                                    $knowledgeBase->video_path,
                                                    $editMatch,
                                                );
                                                $editYoutubeId = $editMatch[1] ?? '';
                                                $editThumbnailUrl = $editYoutubeId
                                                    ? "https://img.youtube.com/vi/{$editYoutubeId}/0.jpg"
                                                    : '';
                                            @endphp
                                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer"
                                                style="width: 280px; flex-shrink: 0;"
                                                onclick="openMediaModal('{{ $editVideoUrl }}', 'youtube')">
                                                <div
                                                    style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #000;">
                                                    @if ($editThumbnailUrl)
                                                        <img src="{{ $editThumbnailUrl }}" alt="YouTube Video"
                                                            class="w-full h-full object-cover opacity-80">
                                                    @endif
                                                    <div
                                                        style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                                                        <div
                                                            style="width: 48px; height: 48px; background: #dc2626; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                                                            <i class="fas fa-play text-white"
                                                                style="margin-left: 2px;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="p-3 text-xs text-gray-500 font-semibold border-t flex items-center justify-between">
                                                    <span><i class="fab fa-youtube text-red-500 mr-1"></i> Video
                                                        YouTube</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer"
                                                style="width: 280px; flex-shrink: 0;"
                                                onclick="openMediaModal('{{ asset('storage/' . $knowledgeBase->video_path) }}', 'video')">
                                                <div
                                                    style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #000; display: flex; align-items: center; justify-content: center;">
                                                    <video src="{{ asset('storage/' . $knowledgeBase->video_path) }}"
                                                        class="w-full h-full object-cover opacity-60"></video>
                                                    <div
                                                        style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                                                        <div
                                                            style="width: 48px; height: 48px; background: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.4);">
                                                            <i class="fas fa-play text-white"
                                                                style="margin-left: 2px;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="p-3 text-xs text-gray-500 font-semibold border-t flex items-center justify-between">
                                                    <span><i class="fas fa-video mr-1 text-blue-500"></i> Video
                                                        Panduan</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('knowledge-base.index') }}'">
                                    Batal
                                </x-button.cancel>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Modal Preview Media --}}
@push('scripts')
    <script>
        function openMediaModal(url, type) {
            const modal = document.getElementById('editMediaPreviewModal');
            const container = document.getElementById('editModalContentContainer');

            let contentHtml = '';
            if (type === 'image') {
                contentHtml =
                    `<img src="${url}" style="max-width: 100%; max-height: 85vh; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); object-fit: contain;">`;
            } else if (type === 'video') {
                contentHtml =
                    `<video src="${url}" controls autoplay style="max-width: 100%; max-height: 85vh; border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); outline: none; background: #000;"></video>`;
            } else if (type === 'youtube') {
                contentHtml = `
                    <div style="position: relative; width: 100%; aspect-ratio: 16/9; max-height: 80vh; border-radius: 12px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                        <iframe src="${url}?autoplay=1" style="position: absolute; top:0; left:0; width:100%; height:100%; border:none;" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    </div>
                `;
            }

            container.innerHTML = contentHtml;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeMediaModal() {
            const modal = document.getElementById('editMediaPreviewModal');
            const container = document.getElementById('editModalContentContainer');
            container.innerHTML = '';
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }

        function closeMediaModalOnBackdrop(e) {
            if (e.target.id === 'editMediaPreviewModal') {
                closeMediaModal();
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMediaModal();
        });
    </script>

    {{-- Modal HTML --}}
    <div id="editMediaPreviewModal"
        style="display: none; position: fixed; inset: 0; z-index: 9999; background-color: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px;"
        onclick="closeMediaModalOnBackdrop(event)">
        <div style="position: absolute; top: 20px; right: 20px; z-index: 10000;">
            <button onclick="closeMediaModal()" class="text-white hover:text-gray-300 transition-colors"
                style="background: none; border: none; font-size: 32px; cursor: pointer; outline: none; padding: 5px;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="editModalContentContainer"
            style="width: 100%; max-width: 900px; display: flex; align-items: center; justify-content: center; position: relative;">
        </div>
    </div>
@endpush
