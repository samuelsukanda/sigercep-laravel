@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Manajemen Risiko</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('komite-mutu.manajemen-risiko.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="list-disc list-inside text-red-600 text-sm">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

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
                                <x-form.input name="nilai" label="Nilai Risiko" :value="old('nilai', $mutu->nilai ?? '')" id="nilai"
                                    readonly />

                                {{-- Keterangan --}}
                                <x-form.textarea name="keterangan" label="Keterangan Monitoring Risiko" :value="old('keterangan', $mutu->keterangan ?? '')"
                                    required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.manajemen-risiko.index') }}"
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
    <script src="{{ asset('assets/js/hitung-risiko.js') }}"></script>
@endpush
