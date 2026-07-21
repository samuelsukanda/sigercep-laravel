@extends('layouts.app')

@section('title', 'Knowledge Base - ' . $knowledgeBase->title)

@section('content')
    <div class="w-full px-6 py-6 mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            {{-- Konten Utama --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <div class="p-6 md:p-8">

                        {{-- Breadcrumb inside card --}}
                        <nav class="flex mb-4" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center text-xs text-gray-400" style="gap: 4px;">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('knowledge-base.index') }}"
                                        class="hover:text-blue-500 transition-colors">Knowledge Base</a>
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-chevron-right mx-2" style="font-size: 9px; color: #cbd5e1;"></i>
                                    <span>{{ $knowledgeBase->category ?? 'Umum' }}</span>
                                </li>
                            </ol>
                        </nav>

                        {{-- Header Artikel --}}
                        <div class="mb-5 pb-5" style="border-bottom: 1px solid #f1f5f9;">
                            @if ($knowledgeBase->status === 'draft')
                                <span
                                    class="inline-block px-2.5 py-1 mb-2 rounded-md text-xs font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">
                                    Mode Draft
                                </span>
                            @endif
                            <h2 class="font-bold text-gray-800 mb-3 leading-snug" style="font-size: 20px;">
                                {{ $knowledgeBase->title }}</h2>

                            <div
                                style="display: flex; flex-wrap: wrap; align-items: center; gap: 14px; font-size: 13px; color: #64748b;">
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-user" style="font-size: 12px; color: #94a3b8;"></i>
                                    <span>{{ ucwords(str_replace('.', ' ', $knowledgeBase->author?->name ?? 'Unknown')) }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-calendar-alt" style="font-size: 12px; color: #94a3b8;"></i>
                                    <span>{{ $knowledgeBase->created_at->format('d M Y') }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <i class="fas fa-eye" style="font-size: 12px; color: #94a3b8;"></i>
                                    <span>{{ number_format($knowledgeBase->views) }} views</span>
                                </div>
                            </div>
                        </div>

                        {{-- Isi Artikel --}}
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($knowledgeBase->content)) !!}
                        </div>

                        {{-- Lampiran Media --}}
                        @if ($knowledgeBase->photo_path || $knowledgeBase->video_path)
                            <div class="mt-8 pt-6 border-t">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">Lampiran</h3>

                                <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                                    @if ($knowledgeBase->photo_path)
                                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer"
                                            style="width: 280px; flex-shrink: 0;"
                                            onclick="openMediaModal('{{ asset('storage/' . $knowledgeBase->photo_path) }}', 'image')">
                                            <div
                                                style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #f8fafc;">
                                                <img src="{{ asset('storage/' . $knowledgeBase->photo_path) }}"
                                                    alt="Foto Tutorial" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-20 transition-all flex items-center justify-center text-white opacity-0 hover:opacity-100"
                                                    style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(0,0,0,0.2);">
                                                    <i class="fas fa-expand text-lg text-white"></i>
                                                </div>
                                            </div>
                                            <div
                                                class="p-3 text-xs text-gray-500 font-semibold border-t flex items-center justify-between">
                                                <span><i class="fas fa-image mr-1 text-blue-500"></i> Foto Lampiran</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($knowledgeBase->video_path)
                                        @if (str_contains($knowledgeBase->video_path, 'youtube.com') || str_contains($knowledgeBase->video_path, 'youtu.be'))
                                            @php
                                                $videoUrl = $knowledgeBase->video_path;
                                                if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                                    $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                                    if (str_contains($videoUrl, '&')) {
                                                        $videoUrl = substr($videoUrl, 0, strpos($videoUrl, '&'));
                                                    }
                                                } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                                    $videoUrl = str_replace(
                                                        'youtu.be/',
                                                        'youtube.com/embed/',
                                                        $videoUrl,
                                                    );
                                                }

                                                // Extract youtube ID for thumbnail
                                                preg_match(
                                                    '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+\?v=)|youtu\.be/)([^"&?/\s]{11})%i',
                                                    $knowledgeBase->video_path,
                                                    $match,
                                                );
                                                $youtubeId = $match[1] ?? '';
                                                $thumbnailUrl = $youtubeId
                                                    ? "https://img.youtube.com/vi/{$youtubeId}/0.jpg"
                                                    : '';
                                            @endphp
                                            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all cursor-pointer"
                                                style="width: 280px; flex-shrink: 0;"
                                                onclick="openMediaModal('{{ $videoUrl }}', 'youtube')">
                                                <div
                                                    style="width: 100%; height: 160px; overflow: hidden; position: relative; background: #000;">
                                                    @if ($thumbnailUrl)
                                                        <img src="{{ $thumbnailUrl }}" alt="YouTube Video"
                                                            class="w-full h-full object-cover opacity-80">
                                                    @endif
                                                    <div class="absolute inset-0 flex items-center justify-center"
                                                        style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                                                        <div class="bg-red-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:scale-110 transition-all"
                                                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                            <i class="fas fa-play text-xs text-white"
                                                                style="margin-left: 2px;"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="p-3 text-xs text-gray-500 font-semibold border-t flex items-center justify-between">
                                                    <span><i class="fab fa-youtube text-red-500 mr-1"></i> Video
                                                        YouTube</span>
                                                    <span class="text-blue-600">Klik untuk memutar</span>
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
                                                    <div class="absolute inset-0 flex items-center justify-center"
                                                        style="position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;">
                                                        <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:scale-110 transition-all"
                                                            style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                                                            <i class="fas fa-play text-xs text-white"
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
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Media Preview Modal --}}
                        <div id="mediaPreviewModal"
                            style="display: none; position: fixed; inset: 0; z-index: 9999; background-color: rgba(15, 23, 42, 0.9); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px;"
                            onclick="closeMediaModalOnBackdrop(event)">
                            <div style="position: absolute; top: 20px; right: 20px; z-index: 10000;">
                                <button onclick="closeMediaModal()" class="text-white hover:text-gray-300 transition-colors"
                                    style="background: none; border: none; font-size: 32px; cursor: pointer; outline: none; padding: 5px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="modalContentContainer"
                                style="width: 100%; max-width: 900px; display: flex; align-items: center; justify-content: center; position: relative;">
                                {{-- Content will be injected by JavaScript --}}
                            </div>
                        </div>
                    </div>

                    {{-- Action Admin/IT --}}
                    @php
                        $user = auth()->user();
                        $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
                        $canEdit = $isIT || $knowledgeBase->author_id === $user->id;
                    @endphp

                    @if ($canEdit)
                        <div
                            style="margin-top: 24px; padding-top: 11px; margin-bottom: 10px; margin-left: 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-start; gap: 10px;">
                            <a href="{{ route('knowledge-base.edit', $knowledgeBase) }}"
                                style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 13px; font-weight: 600; color: #fff; background-color: #3b82f6; border-radius: 8px; text-decoration: none; transition: background 0.2s;"
                                onmouseover="this.style.backgroundColor='#2563eb'"
                                onmouseout="this.style.backgroundColor='#3b82f6'">
                                <i class="fas fa-edit"></i> Edit Artikel
                            </a>
                            <form action="{{ route('knowledge-base.destroy', $knowledgeBase) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="delete-button"
                                    data-confirm="Apakah Anda yakin ingin menghapus artikel ini?"
                                    style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; font-size: 13px; font-weight: 600; color: #fff; background-color: #ef4444; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s;"
                                    onmouseover="this.style.backgroundColor='#dc2626'"
                                    onmouseout="this.style.backgroundColor='#ef4444'">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Box Buat Tiket --}}
            <div
                style="background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-top: 8px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                    <div
                        style="width: 38px; height: 38px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fas fa-headset" style="font-size: 16px; color: #3b82f6;"></i>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: #1e293b;">Masih Butuh Bantuan?</h3>
                </div>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 16px; line-height: 1.6;">Jika artikel ini tidak
                    menyelesaikan masalah Anda, silakan buat tiket untuk bantuan lebih lanjut.</p>
                <a href="{{ route('helpdesk.create', ['from_kb' => 1, 'kb_title' => $knowledgeBase->title]) }}"
                    style="display: block; width: 100%; text-align: center; background: #3b82f6; color: #fff; font-weight: 700; font-size: 13px; padding: 10px 16px; border-radius: 8px; text-decoration: none; transition: background 0.2s;"
                    onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                    <i class="fas fa-ticket-alt" style="margin-right: 6px;"></i> Buat Tiket Bantuan
                </a>
            </div>

            {{-- Artikel Terkait --}}
            @if ($related->count() > 0)
                <div
                    style="background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                        <div
                            style="width: 38px; height: 38px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-list" style="font-size: 15px; color: #3b82f6;"></i>
                        </div>
                        <h3 style="font-size: 15px; font-weight: 700; margin: 0; color: #1e293b;">Artikel Terkait</h3>
                    </div>
                    <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0;">
                        @foreach ($related as $rel)
                            <li style="border-bottom: 1px solid #f1f5f9; padding-bottom: 12px; margin-bottom: 12px; last-child:border-0;">
                                <a href="{{ route('knowledge-base.show', $rel) }}"
                                    style="display: block; text-decoration: none;"
                                    onmouseover="this.querySelector('.rel-title').style.color='#3b82f6'"
                                    onmouseout="this.querySelector('.rel-title').style.color='#374151'">
                                    <h4 class="rel-title"
                                        style="font-size: 13px; font-weight: 600; color: #374151; margin: 0 0 4px 0; line-height: 1.4; transition: color 0.15s; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $rel->title }}</h4>
                                    <p style="font-size: 12px; color: #94a3b8; margin: 0; display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-eye" style="font-size: 10px;"></i>
                                        {{ number_format($rel->views) }} views
                                    </p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openMediaModal(url, type) {
            const modal = document.getElementById('mediaPreviewModal');
            const container = document.getElementById('modalContentContainer');

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
            document.body.style.overflow = 'hidden'; // prevent background scrolling
        }

        function closeMediaModal() {
            const modal = document.getElementById('mediaPreviewModal');
            const container = document.getElementById('modalContentContainer');

            container.innerHTML = '';
            modal.style.display = 'none';
            document.body.style.overflow = ''; // restore scrolling
        }

        function closeMediaModalOnBackdrop(e) {
            if (e.target.id === 'mediaPreviewModal') {
                closeMediaModal();
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMediaModal();
        });
    </script>
@endpush
