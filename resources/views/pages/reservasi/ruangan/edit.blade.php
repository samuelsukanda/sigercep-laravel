@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Reservasi Ruangan</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('reservasi.ruangan.update', $reservasi->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

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
                                <x-form.input name="nama" label="Nama" :value="old('nama', $reservasi->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $reservasi->unit ?? '')" required />

                                {{-- Jam Mulai --}}
                                <x-form.input type="time" name="jam_mulai" label="Jam Mulai" :value="old('jam_mulai', $reservasi->jam_mulai ?? '')"
                                    required />

                                {{-- Jam Selesai --}}
                                <x-form.input type="time" name="jam_selesai" label="Jam Selesai" :value="old('jam_selesai', $reservasi->jam_selesai ?? '')"
                                    required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $reservasi->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Ruang --}}
                                <x-form.select name="ruang" label="Ruang" :options="config('units.ruang')" :selected="old('ruang', $reservasi->ruang ?? '')" required />

                                {{-- Approval --}}
                                <x-form.select name="approval" label="Approval" :options="config('units.approval')" :selected="old('approval', $reservasi->approval ?? '')"
                                    required />

                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('reservasi.ruangan.index') }}'">
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
