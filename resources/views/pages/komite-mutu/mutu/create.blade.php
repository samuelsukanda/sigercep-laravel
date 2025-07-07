@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Mutu</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('komite-mutu.mutu.store') }}" method="POST"
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
                                {{-- Judul Indikator --}}
                                <x-form.input name="indikator" label="Judul Indikator" :value="old('indikator', $mutu->indikator ?? '')" required />

                                {{-- Periode --}}
                                <x-form.input name="periode" label="Periode" :value="old('periode', $mutu->periode ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $mutu->unit ?? '')" required />

                                {{-- PJ Data --}}
                                <x-form.input name="pj_data" label="Penanggung Jawab Data" :value="old('pj_data', $mutu->pj_data ?? '')" required />

                                {{-- Numerator --}}
                                <x-form.input name="numerator" label="Numerator (N)" :value="old('numerator', $mutu->numerator ?? '')" required />

                                {{-- Penumerator --}}
                                <x-form.input name="penumerator" label="Penumerator (D)" :value="old('penumerator', $mutu->penumerator ?? '')" required />

                                {{-- Capaian --}}
                                <x-form.input name="capaian" label="Capaian (%)" :value="old('capaian', $mutu->capaian ?? '')" required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('komite-mutu.mutu.index') }}"
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
