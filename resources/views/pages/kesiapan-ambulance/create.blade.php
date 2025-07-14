@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Kesiapan Ambulance</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form id="form" action="{{ route('kesiapan-ambulance.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Mobil Ambulance --}}
                                <x-form.radio name="mobil_ambulance" label="Mobil Ambulance" :options="[
                                    'Ambulance Luxio T 9931 TB' => 'Ambulance Luxio T 9931 TB',
                                    'Ambulance Alphard T 9905 Z' => 'Ambulance Alphard T 9905 Z',
                                ]"
                                    :selected="old('mobil_ambulance', $ambulance->mobil_ambulance ?? '')" required />

                                {{-- Tanggal --}}
                                <x-form.input name="tanggal" label="Tanggal" :value="old('tanggal', $ambulance->tanggal ?? '')" id="tanggal"
                                    placeholder="Pilih Tanggal" required />

                                {{-- Perawat --}}
                                <x-form.select name="perawat" label="Perawat" :options="config('units.perawat')" :selected="old('perawat', $ambulance->perawat ?? '')"
                                    required />

                                {{-- Kondisi Mobil --}}
                                <x-form.radio-with-input name="kondisi_mobil" label="Kondisi Mobil" :options="[
                                    'Siap' => 'Siap',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('kondisi_mobil', $ambulance->kondisi_mobil ?? '')" required />

                                {{-- Kondisi Driver --}}
                                <x-form.radio-with-input name="kondisi_driver" label="Kondisi Driver" :options="[
                                    'Siap' => 'Siap',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('kondisi_driver', $ambulance->kondisi_driver ?? '')" required />

                                {{-- Oksigen --}}
                                <x-form.radio-with-input name="oksigen" label="Oksigen" :options="[
                                    'Ada' => 'Ada',
                                    'Harus diisi' => 'Harus diisi',
                                    'Other' => 'Other',
                                ]" :selected="old('oksigen', $ambulance->oksigen ?? '')"
                                    required />

                                {{-- Regulator Oksigen --}}
                                <x-form.radio-with-input name="regulator_oksigen" label="Regulator Oksigen"
                                    :options="[
                                        'Bagus' => 'Bagus',
                                        'Rusak' => 'Rusak',
                                        'Other' => 'Other',
                                    ]" :selected="old('regulator_oksigen', $ambulance->regulator_oksigen ?? '')" required />

                                {{-- Kebersihan --}}
                                <x-form.radio-with-input name="kebersihan" label="Kebersihan" :options="[
                                    'Bersih' => 'Bersih',
                                    'Kotor' => 'Kotor',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('kebersihan', $ambulance->kebersihan ?? '')" required />

                                {{-- Monitor Pasien --}}
                                <x-form.radio-with-input name="monitor_pasien" label="Monitor Pasien" :options="[
                                    'Siap Pakai' => 'Siap Pakai',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('monitor_pasien', $ambulance->monitor_pasien ?? '')" required />

                                {{-- AED --}}
                                <x-form.radio-with-input name="aed" label="AED" :options="[
                                    'Ada' => 'Ada',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]" :selected="old('aed', $ambulance->aed ?? '')"
                                    required />

                                {{-- Suction --}}
                                <x-form.radio-with-input name="suction" label="Suction" :options="[
                                    'Ada' => 'Ada',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('suction', $ambulance->suction ?? '')" required />

                                {{-- Ventilator --}}
                                <x-form.radio-with-input name="ventilator" label="Ventilator" :options="[
                                    'Ada' => 'Ada',
                                    'Rusak' => 'Rusak',
                                    'Belum Tersedia' => 'Belum Tersedia',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('desain', $ambulance->ventilator ?? '')" required />

                                {{-- Bed Pasien --}}
                                <x-form.radio-with-input name="bed_pasien" label="Bed Pasien" :options="[
                                    'Ada' => 'Ada',
                                    'Tidak Ada' => 'Tidak Ada',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('bed_pasien', $ambulance->bed_pasien ?? '')" required />

                                {{-- Linen --}}
                                <x-form.radio-with-input name="linen" label="Linen" :options="[
                                    'Tersedia' => 'Tersedia',
                                    'Tidak Tersedia' => 'Tidak Tersedia',
                                    'Other' => 'Other',
                                ]" :selected="old('linen', $ambulance->linen ?? '')"
                                    required />

                                {{-- Obat-Obatan Emergency --}}
                                <x-form.radio-with-input name="obat" label="Obat-Obatan Emergency" :options="[
                                    'Ada' => 'Ada',
                                    'Tidak Tersedia' => 'Tidak Tersedia',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('obat', $ambulance->obat ?? '')" required />

                                {{-- Kelistrikan / Inverter --}}
                                <x-form.radio-with-input name="inverter" label="Kelistrikan / Inverter" :options="[
                                    'Baik' => 'Baik',
                                    'Rusak' => 'Rusak',
                                    'Other' => 'Other',
                                ]"
                                    :selected="old('inverter', $ambulance->inverter ?? '')" required />
                            </div>

                            <div class="mt-6">
                                <x-button.submit type="submit">Simpan</x-button.submit>
                                <a href="{{ route('kesiapan-ambulance.index') }}"
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
