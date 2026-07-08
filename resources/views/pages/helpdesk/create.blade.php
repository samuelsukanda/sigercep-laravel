@extends('layouts.app')

@section('title', 'SIGERCEP - Tambah Tiket Helpdesk')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Tiket Helpdesk</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('helpdesk.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama Pelapor --}}
                                <x-form.input name="name" label="Nama Pelapor" :value="ucfirst(auth()->user()->name)" readonly />

                                {{-- Unit --}}
                                <x-form.input name="unit_name" label="Unit" :value="auth()->user()->unit ?? '-'" readonly />

                                {{-- Deskripsi --}}
                                <x-form.textarea name="description" label="Deskripsi" :value="old('description', $tickets->description ?? '')" required />

                                {{-- Lampiran --}}
                                <x-form.multi-file-upload name="attachment" label="Lampiran Pendukung" :current="$tickets->attachment ?? null" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('helpdesk.index') }}"
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

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="{{ asset('assets/js/multi-upload-file.js') }}"></script>
@endpush
