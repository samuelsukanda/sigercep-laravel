@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Visitasi</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('visitasi.update', $visitasi->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $visitasi->nama ?? '')" required />

                                {{-- Tim --}}
                                <x-form.select name="tim" label="Tim" :options="config('units.tim')" :selected="old('tim', $visitasi->tim ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $visitasi->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Kendala --}}
                                <x-form.textarea label="Kendala Atau Pengaduan Di Lapangan" name="kendala" rows="5"
                                    required>{{ old('kendala', $visitasi->kendala) }}</x-form.textarea>

                                {{-- Foto --}}
                                <x-form.file-upload label="Foto Komplain/Kerusakan/Kendala Di Lapangan" name="foto"
                                    preview="{{ $visitasi->foto ?? null }}" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('visitasi.index') }}'">
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

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush
