@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Reservasi Kendaraan</h6>
                    </div>
                    <div class="flex-auto p-6">

                        @if ($errors->any())
                            <div
                                class="relative w-full p-4 mb-4 text-white border border-red-300 border-solid rounded-lg bg-gradient-to-tl from-red-600 to-orange-600">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('reservasi.kendaraan.update', $reservasi->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $reservasi->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $reservasi->unit ?? '')" required />

                                {{-- Tempat Tujuan --}}
                                <x-form.input name="tempat_tujuan" label="Tempat Tujuan" :value="old('tempat_tujuan', $reservasi->tempat_tujuan ?? '')" required />

                                {{-- Keperluan --}}
                                <x-form.input name="keperluan" label="Keperluan" :value="old('keperluan', $reservasi->keperluan ?? '')" required />

                                {{-- Jam Berangkat --}}
                                <x-form.input type="time" name="jam_berangkat" label="Jam Berangkat" :value="old('jam_berangkat', $reservasi->jam_berangkat ?? '')"
                                    required />

                                {{-- Jam Pulang --}}
                                <x-form.input type="time" name="jam_pulang" label="Jam Pulang" :value="old('jam_pulang', $reservasi->jam_pulang ?? '')"
                                    required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $reservasi->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Jenis Kendaraan --}}
                                <x-form.select name="jenis_kendaraan" label="Jenis Kendaraan" :options="config('units.jenis_kendaraan')"
                                    :selected="old('jenis_kendaraan', $reservasi->jenis_kendaraan ?? '')" required />

                                {{-- Jumlah Penumpang --}}
                                <x-form.select name="jumlah_penumpang" label="Jumlah Penumpang" :options="config('units.jumlah_penumpang')"
                                    :selected="old('jumlah_penumpang', $reservasi->jumlah_penumpang ?? '')" required />

                                {{-- Waktu Tempuh --}}
                                <x-form.select name="waktu_tempuh" label="Waktu Tempuh" :options="config('units.waktu_tempuh')"
                                    :selected="old('waktu_tempuh', $reservasi->waktu_tempuh ?? '')" required />

                                {{-- Jarak Tempuh --}}
                                <x-form.select name="jarak_tempuh" label="Jarak Tempuh" :options="config('units.jarak_tempuh')"
                                    :selected="old('jarak_tempuh', $reservasi->jarak_tempuh ?? '')" required />

                                {{-- Jenis Layanan --}}
                                <x-form.select name="jenis_layanan" label="Jenis Layanan" :options="config('units.jenis_layanan')"
                                    :selected="old('jenis_layanan', $reservasi->jenis_layanan ?? '')" required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('reservasi.kendaraan.index') }}'">
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
