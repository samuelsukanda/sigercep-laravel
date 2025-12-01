@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Mutu</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Judul Indikator --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Judul Indikator</label>
                                <p class="text-slate-600">{{ $mutu->indikator }}</p>
                            </div>

                            {{-- Periode --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Periode</label>
                                <p class="text-slate-600">{{ $mutu->periode }}</p>
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ $mutu->unit }}</p>
                            </div> 
                            
                            {{-- PJ Data --}}  
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Penanggung Jawab Data</label>
                                <p class="text-slate-600">{{ $mutu->pj_data }}</p>
                            </div>
                            
                            {{-- Numerator --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Numerator (N)</label>
                                <p class="text-slate-600">{{ $mutu->numerator }}</p>
                            </div>

                            {{-- Penumerator --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Penumerator (D)</label>
                                <p class="text-slate-600">{{ $mutu->penumerator }}</p>
                            </div>

                            {{-- Capaian --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Capaian (%)</label>
                                <p class="text-slate-600">{{ $mutu->capaian }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('komite-mutu.mutu.index') }}"
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
