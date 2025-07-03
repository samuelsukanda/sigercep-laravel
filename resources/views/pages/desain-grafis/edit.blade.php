@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Desain Grafis</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('desain-grafis.update', $desain->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $desain->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $desain->unit ?? '')" required />

                                {{-- Keperluan --}}
                                <x-form.input name="keperluan" label="Keperluan" :value="old('keperluan', $desain->keperluan ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $desain->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Desain --}}
                                <x-form.radio name="desain" label="Desain" :options="[
                                    'Foto' => 'Foto',
                                    'Video' => 'Video',
                                    'Spanduk' => 'Spanduk',
                                    'Brosur' => 'Brosur',
                                    'Roll Up Banner' => 'Roll Up Banner',
                                ]" :selected="old('desain', $desain->desain ?? '')" required />

                                {{-- Ukuran Desain --}}
                                <div id="form-ukuran">
                                    {{-- Panjang --}}
                                    <div class="w-32 ml-1">
                                        <x-form.input name="panjang" type="number" label="Panjang" :value="old('panjang', $desain->panjang ?? '')"
                                            required />
                                    </div>

                                    {{-- Tinggi --}}
                                    <div class="w-32 ml-1">
                                        <x-form.input name="tinggi" type="number" label="Tinggi" :value="old('tinggi', $desain->tinggi ?? '')"
                                            required />
                                    </div>

                                    {{-- Satuan --}}
                                    <div class="w-40 ml-1">
                                        <x-form.select name="satuan" label="Satuan" :options="config('units.satuan')" :selected="old('satuan', $desain->satuan ?? '')"
                                            required />
                                    </div>
                                </div>

                                {{-- Durasi Video --}}
                                <div id="form-durasi">
                                    {{-- Menit --}}
                                    <div class="w-40 ml-1">
                                        <x-form.select name="menit" label="Menit" :options="collect(range(0, 60))->mapWithKeys(fn($i) => [$i => $i])->toArray()" :selected="old('menit', $desain->menit ?? '')"
                                            required />
                                    </div>

                                    {{-- Detik --}}
                                    <div class="w-40 ml-1">
                                        <x-form.select name="detik" label="Detik" :options="collect(range(0, 60))->mapWithKeys(fn($i) => [$i => $i])->toArray()" :selected="old('detik', $desain->detik ?? '')"
                                            required />
                                    </div>
                                </div>

                                {{-- Status --}}
                                <x-form.select label="Status" name="status" :options="['Pending', 'On Progress', 'Done']" :selected="old('status', $desain->status)"
                                    placeholder="Pilih Status" />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('desain-grafis.index') }}'">
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
    <script src="{{ asset('assets/js/desain-grafis.js') }}"></script>
@endpush
