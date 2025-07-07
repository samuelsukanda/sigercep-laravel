@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Desain Grafis</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ $desain->nama }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $desain->unit }}</p>
                            </div>

                            {{-- Keperluan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Keperluan</label>
                                <p class="text-slate-600">{{ $desain->keperluan }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($desain->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Desain --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Desain</label>
                                <p class="text-slate-600">{{ $desain->desain }}</p>
                            </div>

                            {{-- Ukuran Desain --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Ukuran Desain</label>
                                <p class="text-slate-600">
                                    @if (in_array($desain->desain, ['Foto', 'Spanduk', 'Brosur', 'Roll Up Banner']))
                                        @if ($desain->panjang && $desain->tinggi && $desain->satuan)
                                            {{ $desain->panjang }} x {{ $desain->tinggi }} {{ $desain->satuan }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            {{-- Durasi Video --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Durasi Video</label>
                                <p class="text-slate-600">
                                    @if ($desain->desain === 'Video')
                                        @if ($desain->menit !== null && $desain->detik !== null)
                                            {{ $desain->menit }} : {{ $desain->detik }}
                                        @else
                                            -
                                        @endif
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status</label>
                                <p class="text-slate-600">{{ $desain->status ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('desain-grafis.index') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
