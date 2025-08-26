@extends('layouts.app')

@section('title', 'SIGERCEP')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Edit Ceklis Hardware</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <form action="{{ route('hardware.update', $hardware->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <x-form.input name="nama" label="Nama" :value="old('nama', $hardware->nama ?? '')" required />

                                {{-- Unit --}}
                                <x-form.select name="unit" label="Unit" :options="config('units.units')" :selected="old('unit', $hardware->unit ?? '')" required />

                                {{-- Lantai --}}
                                <x-form.select name="lantai" label="Lantai" :options="config('units.lantai')" :selected="old('lantai', $hardware->lantai ?? '')" required />

                                {{-- Hari/Tanggal Pengecekan --}}
                                <x-form.input name="tanggal" label="Hari/Tanggal Pengecekan" :value="old('tanggal', $hardware->tanggal ?? '')"
                                    id="tanggal" placeholder="Pilih Tanggal" required />

                                {{-- Table Checklist Hardware --}}
                                <div class="mt-4">
                                    <table class="w-full border border-gray-400 text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border border-gray-400 px-3 py-2 text-center">No</th>
                                                <th class="border border-gray-400 px-3 py-2 text-center">Tindakan</th>
                                                <th class="border border-gray-400 px-3 py-2 text-center">Cek</th>
                                                <th class="border border-gray-400 px-3 py-2 text-center">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $checklistItems = [
                                                    'Wallpaper backround RS',
                                                    'Pastikan login sistem operasi ada dua (admin dan limit)',
                                                    'Pastikan password admin dan limit terkontrol',
                                                    'Screen saver jalan',
                                                    'Aplikasi remote VNC berjalan',
                                                    'Bersihkan komputer dari software yang tidak diijinkan',
                                                    'Cek kapasitas hardisk sistem operasi C',
                                                    'Jalankan SIMRS HAMORI',
                                                    'IP address sesuai',
                                                    'Ping Local dan Internet berjalan/reply',
                                                    'Printer bisa digunakan',
                                                    'Catridge terisi tinta',
                                                    'Cek nyalanya Monitor',
                                                    'Cek fungsi UPS',
                                                    'Cek fungsi Mouse',
                                                    'Cek fungsi Keyboard',
                                                    'Bersihkan Casing bagian dalam dari debu',
                                                    'Rapihkan pengkabelan',
                                                    'Rapikan penempatan',
                                                ];

                                                // ambil checklist dari DB (sudah disimpan dalam JSON)
                                                $checklistData = json_decode($hardware->checklist, true) ?? [];
                                            @endphp

                                            @foreach ($checklistItems as $index => $item)
                                                @php
                                                    $status = old(
                                                        "checklist.$item.status",
                                                        $checklistData[$item]['status'] ?? 0,
                                                    );
                                                    $keterangan = old(
                                                        "checklist.$item.keterangan",
                                                        $checklistData[$item]['keterangan'] ?? '',
                                                    );
                                                @endphp
                                                <tr>
                                                    <td class="border border-gray-400 px-3 py-2">{{ $index + 1 }}</td>
                                                    <td class="border border-gray-400 px-3 py-2">{{ $item }}</td>
                                                    <td class="border border-gray-400 px-3 py-2 text-center">
                                                        <input type="checkbox" name="checklist[{{ $item }}][status]"
                                                            value="1" class="check-item"
                                                            {{ $status ? 'checked' : '' }}>
                                                    </td>
                                                    <td class="border border-gray-400 px-3 py-2">
                                                        <input type="text"
                                                            name="checklist[{{ $item }}][keterangan]"
                                                            value="{{ $keterangan }}"
                                                            class="w-full border rounded px-2 py-1">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="border px-3 py-2 text-right bg-gray-50">
                                                    <label>
                                                        <input type="checkbox" id="check-all" class="mr-2">
                                                        Check All
                                                    </label>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-6">
                                <x-button.submit>Ubah</x-button.submit>
                                <x-button.cancel type="button" class="ml-2 bg-gray-200"
                                    onclick="window.location='{{ route('hardware.index') }}'">
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

@push('scripts')
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
@endpush
