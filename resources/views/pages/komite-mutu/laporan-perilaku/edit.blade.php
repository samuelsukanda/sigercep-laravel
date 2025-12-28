@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Laporan Perilaku</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komite-mutu.laporan-perilaku.update', $laporanPerilaku->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $laporanPerilaku->nama ?? '')" required />

                                {{-- NIK --}}
                                <x-form.input name="nik" label="NIK" :value="old('nik', $laporanPerilaku->nik ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $laporanPerilaku->unit ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $laporanPerilaku->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Kategori Laporan --}}
                                <x-form.select name="kategori_laporan" label="Kategori Laporan" :options="config('units.kategori_laporan')"
                                    :selected="old('kategori_laporan', $laporanPerilaku->kategori_laporan ?? '')" required />

                                {{-- Keterangan Perilaku --}}
                                <x-form.input name="keterangan_perilaku" label="Keterangan Perilaku" :value="old('keterangan_perilaku', $laporanPerilaku->keterangan_perilaku ?? '')"
                                    required />

                                {{-- Dokumen --}}
                                <x-form.file-upload-pdf name="file_pdf"
                                    label="Upload File (PDF - Kosongkan jika tidak diubah)" />

                                @if ($laporanPerilaku->file_path)
                                    <div class="col-span-2">
                                        <label class="block text-sm font-semibold mb-2 text-slate-700">File Sekarang</label>
                                        <p class="text-slate-600">{{ $laporanPerilaku->file_pdf }}</p>
                                        <a href="{{ route('laporan-perilaku.show-file', $laporanPerilaku->id) }}"
                                            target="_blank"
                                            class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                            📄 Lihat File PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('komite-mutu.laporan-perilaku.index') }}'">
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
