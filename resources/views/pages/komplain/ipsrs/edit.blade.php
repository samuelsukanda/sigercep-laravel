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
                                        class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all placeholder:text-gray-500"
                                        required>
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
                                <div>
                                    <label for="tujuan_unit"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Ditujukan Ke Unit</label>
                                    @php
                                        $units = include resource_path('views/components/tujuan-units-komplain.php');
                                        $tujuanUnits = old('tujuan_unit', $komplain->unit ?? '');
                                    @endphp
                                    <select id="tujuan_unit" name="tujuan_unit"
                                        class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all placeholder:text-gray-500"
                                        required>
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
                                    <input id="tanggal" type="text" name="tanggal"
                                        value="{{ old('tanggal', $komplain->tanggal ?? '') }}" required
                                        class="form-input w-full px-3 py-2 mb-2 border border-gray-300 rounded-lg text-gray-700 placeholder:text-gray-500 outline-none transition-all"
                                        placeholder="Pilih Tanggal" />
                                </div>

                                <!-- Kendala -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Kendala Kendala atau
                                        Pengaduan di lapangan:</label>
                                    <textarea name="kendala" required rows="5"
                                        class="focus:shadow-primary-outline min-h-unset text-sm leading-5.6 ease block h-auto w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ old('kendala', $komplain->kendala ?? '') }}</textarea>
                                </div>

                                <!-- Foto -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2 text-slate-700">Foto
                                        Komplain/Kerusakan/Kendala di Lapangan:</label>
                                    <div class="w-full">
                                        <label for="foto-upload" class="block">
                                            <div id="custom-upload"
                                                class="flex justify-between items-center w-full px-4 py-2 border border-gray-300 rounded-lg bg-white cursor-pointer">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fa-solid fa-file-import text-gray-500 mr-2"></i>
                                                    <span class="text-sm text-gray-700">Pilih File</span>
                                                </div>
                                                <span id="file-name" class="text-sm text-gray-500 truncate"></span>
                                            </div>
                                            <input id="foto-upload" name="foto" type="file" class="hidden" />
                                        </label>

                                        {{-- Preview jika edit --}}
                                        @if (isset($komplain) && $komplain->foto)
                                            <img src="{{ asset('storage/' . $komplain->foto) }}" alt="Foto"
                                                class="mt-2 h-24 rounded shadow-md object-cover border border-gray-200 w-auto max-w-full" />
                                        @endif
                                    </div>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status"
                                        class="block text-sm font-semibold mb-2 text-slate-700">Status</label>
                                    <select id="status" name="status"
                                        class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all placeholder:text-gray-500">
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
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
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

@push('scripts')
    <script src="{{ asset('assets/js/Flatpickr.js') }}"></script>
@endpush
    