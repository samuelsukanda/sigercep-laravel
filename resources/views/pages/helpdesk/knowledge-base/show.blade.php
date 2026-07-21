@extends('layouts.app')

@section('title', 'Knowledge Base - ' . $knowledgeBase->title)

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    
    {{-- Breadcrumb --}}
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
            <li class="inline-flex items-center">
                <a href="{{ route('knowledge-base.index') }}" class="text-gray-500 hover:text-blue-600">
                    <i class="fas fa-book mr-2"></i> Knowledge Base
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-xs mx-1"></i>
                    <span class="text-gray-500 ml-1">{{ $knowledgeBase->category ?? 'Umum' }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        {{-- Konten Utama --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <div class="p-6 md:p-8">
                    {{-- Header Artikel --}}
                    <div class="mb-6 pb-6 border-b">
                        @if($knowledgeBase->status === 'draft')
                            <span class="inline-block px-2.5 py-1 mb-3 rounded-md text-xs font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">
                                Mode Draft
                            </span>
                        @endif
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">{{ $knowledgeBase->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <img src="{{ $knowledgeBase->author?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($knowledgeBase->author?->name ?? 'Unknown').'&background=f8fafc&color=64748b' }}" class="w-6 h-6 rounded-full" alt="avatar">
                                <span class="font-medium text-gray-700">{{ $knowledgeBase->author?->name ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                {{ $knowledgeBase->created_at->format('d M Y') }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <i class="fas fa-eye text-gray-400"></i>
                                {{ number_format($knowledgeBase->views) }} views
                            </div>
                        </div>
                    </div>

                    {{-- Isi Artikel --}}
                    <div class="prose max-w-none text-gray-700">
                        {!! nl2br(e($knowledgeBase->content)) !!}
                    </div>
                    
                    {{-- Lampiran Media --}}
                    @if($knowledgeBase->photo_path || $knowledgeBase->video_url)
                    <div class="mt-8 pt-6 border-t">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Lampiran</h3>
                        
                        @if($knowledgeBase->photo_path)
                            <div class="mb-6">
                                <img src="{{ asset('storage/' . $knowledgeBase->photo_path) }}" alt="Foto Tutorial" class="max-w-full md:max-w-2xl rounded-lg border shadow-sm">
                            </div>
                        @endif
                        
                        @if($knowledgeBase->video_url)
                            <div class="aspect-w-16 aspect-h-9 w-full max-w-2xl rounded-lg overflow-hidden border shadow-sm">
                                @php
                                    $videoUrl = $knowledgeBase->video_url;
                                    if (str_contains($videoUrl, 'youtube.com/watch?v=')) {
                                        $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                                        if (str_contains($videoUrl, '&')) $videoUrl = substr($videoUrl, 0, strpos($videoUrl, '&'));
                                    } elseif (str_contains($videoUrl, 'youtu.be/')) {
                                        $videoUrl = str_replace('youtu.be/', 'youtube.com/embed/', $videoUrl);
                                    }
                                @endphp
                                <iframe src="{{ $videoUrl }}" class="w-full h-80" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <a href="{{ $knowledgeBase->video_url }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 mt-3 font-medium">
                                <i class="fab fa-youtube text-red-500"></i> Buka di YouTube <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Action Admin/IT --}}
                @php
                    $user = auth()->user();
                    $isIT = $user->unit && strtolower($user->unit) == 'teknologi dan informasi';
                    $canEdit = $isIT || $knowledgeBase->author_id === $user->id;
                @endphp
                
                @if($canEdit)
                <div class="bg-gray-50 p-4 border-t flex items-center justify-end gap-3">
                    <a href="{{ route('knowledge-base.edit', $knowledgeBase) }}" class="px-4 py-2 text-sm font-medium text-amber-700 bg-amber-50 border border-amber-200 rounded-lg hover:bg-amber-100 transition">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('knowledge-base.destroy', $knowledgeBase) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Box Buat Tiket --}}
            <div class="bg-blue-600 rounded-xl p-5 text-white shadow-sm">
                <h3 class="font-bold text-lg mb-2">Masih Butuh Bantuan?</h3>
                <p class="text-blue-100 text-sm mb-4">Jika artikel ini tidak menyelesaikan masalah Anda, silakan buat tiket untuk bantuan lebih lanjut.</p>
                <a href="{{ route('helpdesk.create') }}" class="block w-full text-center bg-white text-blue-600 font-bold px-4 py-2 rounded-lg hover:bg-blue-50 transition shadow-sm">
                    Buat Tiket Bantuan
                </a>
            </div>

            {{-- Artikel Terkait --}}
            @if($related->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list text-blue-500"></i> Artikel Terkait
                </h3>
                <ul class="space-y-3">
                    @foreach($related as $rel)
                    <li>
                        <a href="{{ route('knowledge-base.show', $rel) }}" class="block group border-b pb-2 last:border-0 last:pb-0">
                            <h4 class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition line-clamp-2 leading-snug">{{ $rel->title }}</h4>
                            <p class="text-xs text-gray-400 mt-1"><i class="fas fa-eye text-[10px]"></i> {{ number_format($rel->views) }} views</p>
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
