@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Change Request')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Change Request</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('change-request.update', $changeRequest->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Nama (read only) --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">Nama</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-slate-500 text-sm"
                                        value="{{ ucfirst($changeRequest->nama) }}" disabled>
                                </div>

                                {{-- Jabatan (read only) --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">Jabatan</label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-slate-500 text-sm"
                                        value="{{ $changeRequest->user->jabatan ?? ($changeRequest->jabatan ?? '-') }}"
                                        disabled>
                                </div>

                                {{-- Tanggal Permintaan --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">Tanggal
                                        Permintaan</label>
                                    <input type="text" id="created_at" name="created_at"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('created_at') border-red-500 @enderror"
                                        value="{{ old('created_at', \Carbon\Carbon::parse($changeRequest->created_at)->format('d-m-Y')) }}"
                                        placeholder="Pilih Tanggal">
                                    @error('created_at')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status Dokumen --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">
                                        Status Dokumen
                                    </label>
                                    <select name="status_dokumen"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_dokumen') border-red-500 @enderror"
                                        required>
                                        @foreach (['Terpenuhi', 'Dalam Proses', 'Tidak Ada'] as $sd)
                                            <option value="{{ $sd }}"
                                                {{ old('status_dokumen', $changeRequest->status_dokumen ?? 'Dalam Proses') == $sd ? 'selected' : '' }}>
                                                {{ $sd }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_dokumen')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Status Pengerjaan --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">
                                        Status Pengerjaan
                                    </label>
                                    <select name="status_pengerjaan"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status_pengerjaan') border-red-500 @enderror"
                                        required>
                                        @foreach (['Open', 'In Progress', 'Pending', 'QC', 'Done', 'Closed'] as $sp)
                                            <option value="{{ $sp }}"
                                                {{ old('status_pengerjaan', $changeRequest->status_pengerjaan ?? 'Open') == $sp ? 'selected' : '' }}>
                                                {{ $sp }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status_pengerjaan')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- No Tiket --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">No Tiket</label>
                                    <input type="text" name="no_tiket"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_tiket') border-red-500 @enderror"
                                        value="{{ old('no_tiket', $changeRequest->no_tiket) }}"
                                        placeholder="Nomor tiket (opsional)">
                                    @error('no_tiket')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- PIC Request --}}
                                <div>
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">PIC Request</label>
                                    <input type="text" name="pic_request"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('pic_request') border-red-500 @enderror"
                                        value="{{ old('pic_request', $changeRequest->pic_request) }}"
                                        placeholder="Nama PIC (opsional)">
                                    @error('pic_request')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Permintaan Fitur --}}
                                <x-form.select name="permintaan_fitur" label="Permintaan Fitur"
                                    :options="config('units.permintaan_fitur')"
                                    :selected="old('permintaan_fitur', $changeRequest->permintaan_fitur)" required />

                                {{-- Deskripsi --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold mb-1 text-slate-700">
                                        Deskripsi
                                    </label>
                                    <textarea name="deskripsi" rows="5"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                        required>{{ old('deskripsi', $changeRequest->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Upload File Pendukung --}}
                                <div class="md:col-span-2">
                                    <x-form.file-upload-pdf name="file_pendukung" label="Upload File Pendukung" />
                                </div>

                                {{-- File Sekarang --}}
                                @if ($changeRequest->file_path)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold mb-2 text-slate-700">File Sekarang</label>
                                        <a href="{{ route('change-request.show-file', $changeRequest->id) }}"
                                            target="_blank"
                                            class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                            📄 Lihat File PDF
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <a href="{{ route('change-request.index') }}"
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof flatpickr !== "undefined") {
                flatpickr("#created_at", {
                    dateFormat: "d-m-Y"
                });
            }
        });
    </script>
@endpush
