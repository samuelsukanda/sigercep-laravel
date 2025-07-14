@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Kesiapan Ambulance</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Mobil Ambulance --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Mobil Ambulance</label>
                                <p class="text-slate-600">{{ $ambulance->mobil_ambulance }}</p>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($ambulance->tanggal)->translatedFormat('d F Y') }}
                                </p>
                            </div>

                            {{-- Perawat --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Perawat</label>
                                <p class="text-slate-600">{{ $ambulance->perawat }}</p>
                            </div>

                            {{-- Kondisi Mobil --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kondisi Mobil</label>
                                <p class="text-slate-600">{{ $ambulance->kondisi_mobil }}</p>
                            </div>

                            {{-- Kondisi Driver --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kondisi Driver</label>
                                <p class="text-slate-600">{{ $ambulance->kondisi_driver }}</p>
                            </div>

                            {{-- Oksigen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Oksigen</label>
                                <p class="text-slate-600">{{ $ambulance->oksigen }}</p>
                            </div>

                            {{-- Regulator Oksigen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Regulator Oksigen</label>
                                <p class="text-slate-600">{{ $ambulance->regulator_oksigen }}</p>
                            </div>

                            {{-- Kebersihan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kebersihan</label>
                                <p class="text-slate-600">{{ $ambulance->kebersihan }}</p>
                            </div>

                            {{-- Monitor Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Monitor Pasien</label>
                                <p class="text-slate-600">{{ $ambulance->monitor_pasien }}</p>
                            </div>

                            {{-- AED --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">AED</label>
                                <p class="text-slate-600">{{ $ambulance->aed }}</p>
                            </div>

                            {{-- Suction --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Suction</label>
                                <p class="text-slate-600">{{ $ambulance->suction }}</p>
                            </div>

                            {{-- Ventilator --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Ventilator</label>
                                <p class="text-slate-600">{{ $ambulance->ventilator }}</p>
                            </div>

                            {{-- Bed Pasien --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Bed Pasien</label>
                                <p class="text-slate-600">{{ $ambulance->bed_pasien }}</p>
                            </div>

                            {{-- Linen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Linen</label>
                                <p class="text-slate-600">{{ $ambulance->linen }}</p>
                            </div>

                            {{-- Obat-Obatan Emergency --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Obat-Obatan Emergency</label>
                                <p class="text-slate-600">{{ $ambulance->obat }}</p>
                            </div>

                            {{-- Kelistrikan / Inverter --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Kelistrikan / Inverter</label>
                                <p class="text-slate-600">{{ $ambulance->inverter }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('kesiapan-ambulance.index') }}"
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
