@extends('layouts.app')

@section('title', 'SIGERCEP - Tambah Knowledge Base')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Artikel Knowledge Base</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('knowledge-base.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4">
                                <x-form.input name="title" label="Judul Artikel" required />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">Kategori :</label>
                                    <select name="category"
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all placeholder:text-gray-500 focus:outline-none focus:border-blue-500">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat }}"
                                                {{ old('category') == $cat ? 'selected' : '' }}>
                                                {{ $cat }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-semibold text-slate-700">Status :</label>
                                    <select name="status"
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 px-3 py-2 font-normal outline-none transition-all placeholder:text-gray-500 focus:outline-none focus:border-blue-500"
                                        required>
                                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                            Published</option>
                                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                        </option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <x-form.textarea name="content" label="Konten Artikel" required />
                            </div>

                            <div class="mb-4">
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Upload Foto
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
                            </div>

                            <div class="mb-6">
                                <label class="block mb-2 text-sm font-semibold text-slate-700">Upload Video
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
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Simpan</x-button.submit>
                                <a href="{{ route('knowledge-base.index') }}"
                                    class="ml-2 inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
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
