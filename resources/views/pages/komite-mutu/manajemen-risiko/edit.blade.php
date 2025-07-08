@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Manajemen Risiko</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komite-mutu.manajemen-risiko.update', $mutu->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $mutu->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $mutu->unit ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $mutu->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Uraian Risiko --}}
                                <x-form.input name="uraian" label="Uraian Risiko" :value="old('uraian', $mutu->uraian ?? '')" required />

                                {{-- Dampak --}}
                                <x-form.select name="dampak" label="Dampak (D)" :options="config('units.dampak')" :selected="old('dampak', $mutu->dampak ?? '')"
                                    id="dampak" onchange="hitungNilaiRisiko()" required />

                                {{-- Kemungkinan --}}
                                <x-form.select name="kemungkinan" label="Kemungkinan (K)" :options="config('units.kemungkinan')"
                                    :selected="old('kemungkinan', $mutu->kemungkinan ?? '')" id="kemungkinan" onchange="hitungNilaiRisiko()" required />

                                {{-- Nilai Risiko --}}
                                <x-form.input name="nilai" label="Nilai Risiko (D x K)" :value="old('nilai', $mutu->nilai ?? '')" id="nilai"
                                    readonly />

                                {{-- Keterangan --}}
                                <x-form.textarea label="Keterangan Monitoring Risiko" name="keterangan" rows="5"
                                    required>{{ old('keterangan', $mutu->keterangan) }}</x-form.textarea>
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('komite-mutu.manajemen-risiko.index') }}'">
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
