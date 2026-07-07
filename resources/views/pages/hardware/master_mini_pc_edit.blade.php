@extends('layouts.app')

@section('title', 'SIGERCEP - Edit Master Mini PC')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Master Mini PC</h6>
                    </div>
                    <div class="flex-auto p-6">

                        @if ($errors->any())
                            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('hardware.master-mini-pc.update', $master->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                {{-- Nama PC --}}
                                <x-form.input name="nama_pc" label="Nama PC" :value="old('nama_pc', $master->nama_pc)" required />

                                {{-- Jenis PC --}}
                                <x-form.input name="jenis_pc" label="Jenis PC" :value="old('jenis_pc', $master->jenis_pc)" />

                                {{-- Lantai --}}
                                <x-form.input name="lantai" label="Lantai" :value="old('lantai', $master->lantai)" />

                                {{-- IP --}}
                                <x-form.input name="ip" label="IP Komputer" :value="old('ip', $master->ip)" required />

                                {{-- RAM --}}
                                <x-form.input name="ram" label="RAM" :value="old('ram', $master->ram)"
                                    placeholder="Contoh: 8GB DDR4" />

                                {{-- CPU --}}
                                <x-form.input name="cpu" label="CPU / Processor" :value="old('cpu', $master->cpu)"
                                    placeholder="Contoh: Intel Core i5-10400" />

                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('hardware.reports') }}"
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
