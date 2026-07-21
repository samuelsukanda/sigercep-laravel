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
                                <span>{{ $knowledgeBase->author?->name ?? 'Unknown' }}</span>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <x-form.input name="title" label="Judul Artikel" :value="$knowledgeBase->title" required />
                                <x-form.input name="video_url" label="URL Video (Opsional, cth: YouTube)" type="url"
                                    :value="$knowledgeBase->video_url" />
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
                                <x-form.textarea name="description" label="Deskripsi Singkat" :value="$knowledgeBase->description" />
                            </div>

                            <div class="mb-4">
                                <x-form.textarea name="content" label="Konten Artikel" :value="$knowledgeBase->content" required />
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Upload Foto Baru
                                    (Opsional)</label>
                                <input type="file" name="photo" accept="image/*"
                                    class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all focus:outline-none focus:border-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Maks. 5MB (JPG, PNG). Biarkan kosong jika tidak ingin
                                    mengubah foto.</p>
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                @if ($knowledgeBase->photo_path)
                                    <div class="mt-3">
                                        <p class="text-xs text-slate-500 font-semibold mb-2">Foto Saat Ini:</p>
                                        <img src="{{ asset('storage/' . $knowledgeBase->photo_path) }}" alt="Current Photo"
                                            class="h-32 w-auto object-cover rounded-lg border shadow-sm">
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 flex gap-2">
                                <x-button.submit>Simpan Perubahan</x-button.submit>
                                <a href="{{ route('knowledge-base.index') }}"
                                    class="inline-block px-6 py-2 mb-0 text-xs font-semibold text-center text-slate-700 uppercase align-middle transition-all bg-gray-200 border-0 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
