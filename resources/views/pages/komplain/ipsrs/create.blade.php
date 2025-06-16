@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Tambah Komplain IPSRS</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('komplain.ipsrs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nama -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Nama</label>
                                    <input type="text" name="nama" value="{{ old('nama', $komplain->nama ?? '') }}"
                                        required
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></input>
                                </div>

                                <!-- Unit -->
                                <div>
                                    <label for="unit"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Unit</label>
                                    @php
                                        $units = include resource_path('views/components/units.php');
                                        $selectedUnit = old('unit', $komplain->unit ?? '');
                                    @endphp
                                    <select id="unit" name="unit"
                                        class="select2 w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                        <option disabled {{ $selectedUnit == '' ? 'selected' : '' }}>Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}"
                                                {{ $selectedUnit == $unit ? 'selected' : '' }}>
                                                {{ $unit }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                <!-- Tujuan Unit -->
                                <div class="mb-4">
                                    <label for="tujuan_unit"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Ditujukan Ke Unit:</label>
                                    @php
                                        $units = include resource_path('views/components/tujuan-units-komplain.php');
                                        $tujuanUnits = old('tujuan_unit', $komplain->unit ?? '');
                                    @endphp
                                    <select id="tujuan_unit" name="tujuan_unit"
                                        class="select2 w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                                        <option disabled {{ $tujuanUnits == '' ? 'selected' : '' }}>Pilih Unit</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit }}"
                                                {{ $tujuanUnits == $unit ? 'selected' : '' }}>
                                                {{ $unit }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Tanggal -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Tanggal</label>
                                    
                                    <input type="date" name="tanggal"
                                        value="{{ old('tanggal', $komplain->tanggal ?? '') }}" required
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" />
                                </div>

                                <!-- Kendala -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Kendala Kendala atau Pengaduan di lapangan:</label>
                                    <textarea name="kendala" required rows="5" class="focus:shadow-primary-outline min-h-unset text-sm leading-5.6 ease block h-auto w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ old('kendala', $komplain->kendala ?? '') }}</textarea>
                                </div>

                                <!-- Foto -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Foto Komplain/Kerusakan/Kendala di Lapangan:</label>
                                    <input type="file" name="foto"
                                        class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg" />
                                    @if (isset($komplain) && $komplain->foto)
                                        <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto"
                                            class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200" />
                                    @endif
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="inline-block px-6 py-2 mb-0 text-xs font-bold text-center text-slate-700 uppercase align-middle transition-all bg-gradient-to-tl from-blue-600 to-cyan-400 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                    Simpan
                                </button>
                                <a href="{{ route('komplain.ipsrs.index') }}"
                                    class="inline-block ml-2 px-6 py-2 mb-0 text-xs font-semibold text-center text-slate-700 uppercase align-middle transition-all bg-gray-200 rounded-lg hover:bg-gray-300 active:opacity-85">
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
