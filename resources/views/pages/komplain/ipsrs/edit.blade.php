@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Komplain IPSRS</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komplain.ipsrs.update', $komplain->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nama -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama', $komplain->nama ?? '') }}"
                                        required
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
                                </div>

                                <!-- Unit -->
                                <div>
                                    <label for="unit"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Unit</label>
                                    <select id="unit" name="unit" class="form-control select2bs4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500"" required>
                                        <option disabled {{ old('unit', $komplain->unit ?? '') == '' ? 'selected' : '' }}>
                                            Pilih Unit</option>
                                        @php
                                            $units = [
                                                'Admisi dan Billing',
                                                'Akuntansi',
                                                'Casemix',
                                                'CSSD',
                                                'Direktur RS',
                                                'Dokter',
                                                'Farmasi',
                                                'Fisioterapi',
                                                'Gudang',
                                                'Gizi',
                                                'HD',
                                                'ICU',
                                                'IT',
                                                'Kesehatan Lingkungan',
                                                'Kanit',
                                                'Keuangan',
                                                'Komite Medik',
                                                'Komite Mutu',
                                                'Laboratorium',
                                                'Marketing',
                                                'Manager',
                                                'Maintenance',
                                                'NICU dan PICU',
                                                'NS Poli',
                                                'OK',
                                                'Pengadaan',
                                                'Perawat',
                                                'Rawat Inap',
                                                'Radiologi',
                                                'Rekam Medis',
                                                'Sekretaris',
                                                'Security',
                                                'SDM',
                                                'SPV',
                                                'UGD',
                                                'VK',
                                            ];
                                            $selectedUnit = old('unit', $komplain->unit ?? '');
                                        @endphp
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}"
                                                {{ $selectedUnit == $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tujuan Unit -->
                                <div class="mb-4">
                                    <label for="tujuan_unit"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Ditujukan Ke Unit:</label>
                                    <select id="tujuan_unit" name="tujuan_unit" required
                                        class="form-control select2bs4 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">
                                        <option disabled
                                            {{ old('tujuan_unit', $komplain->tujuan_unit ?? '') == '' ? 'selected' : '' }}>
                                            Pilih Unit</option>
                                        @php
                                            $tujuanUnits = [
                                                'Maintenance' => 'Maintenance',
                                                'Kesehatan Lingkungan' => 'Kesehatan Lingkungan',
                                                'Elektromedis (Atem)' => 'Elektromedis (Atem)',
                                            ];
                                            $selectedTujuan = old('tujuan_unit', $komplain->tujuan_unit ?? '');
                                        @endphp
                                        @foreach ($tujuanUnits as $value => $label)
                                            <option value="{{ $value }}"
                                                {{ $selectedTujuan == $value ? 'selected' : '' }}>{{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Tanggal</label>
                                    <input type="date" name="tanggal"
                                        value="{{ old('tanggal', $komplain->tanggal ?? '') }}" required
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
                                </div>

                                <!-- Kendala -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Kendala</label>
                                    <textarea name="kendala" rows="4" required
                                        class="form-textarea w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">{{ old('kendala', $komplain->kendala ?? '') }}</textarea>
                                </div>

                                <!-- Foto -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Foto</label>
                                    <input type="file" name="foto"
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
                                    @if (isset($komplain) && $komplain->foto)
                                        <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto"
                                            class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200" />
                                    @endif
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Status</label>
                                    <select name="status"
                                        class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500">
                                        <option value="">Pilih Status</option>
                                        @foreach (['Pending', 'On Progress', 'Done'] as $status)
                                            <option value="{{ $status }}"
                                                {{ old('status', $komplain->status ?? '') === $status ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Keterangan -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Keterangan</label>
                                    <input type="text" name="keterangan"
                                        value="{{ old('keterangan', $komplain->keterangan ?? '') }}"
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500" />
                                </div>
                            </div>


                            <div class="mt-6">
                                <button type="submit"
                                    class="inline-block px-6 py-2 mb-0 text-xs font-bold text-center text-slate-700 uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Edit
                                </button>
                                <a href="{{ route('komplain.ipsrs.index') }}"
                                    class="inline-block ml-2 px-6 py-2 mb-0 text-xs font-semibold text-center text-slate-700 uppercase align-middle transition-all bg-gray-200 rounded-lg hover:bg-gray-300 active:opacity-85">
                                    Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
