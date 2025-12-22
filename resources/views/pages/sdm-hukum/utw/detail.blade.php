@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail UTW</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Nama File --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama File</label>
                                <p class="text-slate-600">{{ $utw->file_pdf }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $utw->unit }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($utw->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- File UTW --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">File UTW</label>
                                <p class="text-slate-600">{{ $utw->file_pdf }}</p>
                                <a href="{{ route('utw.show-file', $utw->id) }}" target="_blank"
                                    class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                    📄 Lihat File PDF
                                </a>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('sdm-hukum.utw.index') }}"
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
