@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Mutu</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komite-mutu.mutu.update', $mutu->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Judul Indikator --}}
                                <x-form.input name="indikator" label="Judul Indikator" :value="old('indikator', $mutu->indikator)" required />

                                {{-- Periode --}}
                                <x-form.input name="periode" label="Periode" :value="old('periode', $mutu->periode)" required />

                                {{-- Unit --}}
                                <x-form.select label="Unit" name="unit" :options="config('units.units')" :selected="old('unit', $mutu->unit)"
                                    placeholder="Pilih Unit" required />

                                {{-- PJ Data --}}
                                <x-form.input name="pj_data" label="Penanggung Jawab Data" :value="old('pj_data', $mutu->pj_data)" required />

                                {{-- Numerator --}}
                                <x-form.input name="numerator" label="Numerator (N)" :value="old('numerator', $mutu->numerator)" required />

                                {{-- Penumerator --}}
                                <x-form.input name="penumerator" label="Penumerator (D)" :value="old('penumerator', $mutu->penumerator)" required />

                                {{-- Capaian --}}
                                <x-form.input name="capaian" label="Capaian (%)" :value="old('capaian', $mutu->capaian)" required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('komite-mutu.mutu.index') }}'">
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
