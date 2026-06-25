@extends('layouts.app')

@section('title', 'SIGERCEP - Device & Printer')

@push('styles')
    <style>
        @media (min-width: 1280px) {
            .modal-overlay {
                left: 17rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- Flash Message --}}
                @if (session('success'))
                    <div
                        class="relative text-s w-full p-4 mb-4 text-white border border-blue-300 border-solid rounded-lg bg-gradient-to-tl from-blue-500 to-violet-500">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl flex justify-between items-center">
                        <h6 class="mb-0 font-bold text-lg">Detail Device & Printer PC</h6>
                        <button type="button" onclick="openModal()"
                            class="inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white uppercase rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                            style="background-color: #7664E4 !important;">
                            <i class="fas fa-plus mr-1"></i> Tambah Device & Printer
                        </button>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                            {{-- Nama PC --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama PC</label>
                                <p class="text-slate-600">{{ $pcData->nama_pc }}</p>
                            </div>
                            {{-- IP --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">IP Komputer</label>
                                <p class="text-slate-600">{{ $pcData->ip }}</p>
                            </div>
                            {{-- Unit --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Unit</label>
                                <p class="text-slate-600">{{ empty($pcData->unit) ? '-' : $pcData->unit }}</p>
                            </div>
                            {{-- Lantai --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lantai</label>
                                <p class="text-slate-600">{{ $pcData->lantai }}</p>
                            </div>
                            {{-- Spesifikasi --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Spesifikasi</label>
                                <p class="text-slate-600" style="white-space: pre-line;">{{ empty($pcData->spesifikasi) ? '-' : $pcData->spesifikasi }}</p>
                            </div>
                        </div>

                        {{-- Table Device & Printer --}}
                        <div class="mt-4">
                            <h6 class="mb-3 font-semibold text-md">Daftar Device & Printer Terhubung</h6>
                            <div class="overflow-x-auto">
                                <table class="w-full border border-gray-200 text-sm rounded-lg">
                                    <thead class="bg-gray-100 text-slate-600">
                                        <tr>
                                            <th class="border border-gray-200 px-3 py-2 text-center w-12">No</th>
                                            <th class="border border-gray-200 px-3 py-2 text-left">Nama Perangkat</th>
                                            <th class="border border-gray-200 px-3 py-2 text-center">Jenis</th>
                                            <th class="border border-gray-200 px-3 py-2 text-center">Merk/Type</th>
                                            <th class="border border-gray-200 px-3 py-2 text-center">Kondisi</th>
                                            <th class="border border-gray-200 px-3 py-2 text-left">Keterangan</th>
                                            <th class="border border-gray-200 px-3 py-2 text-center w-20">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($devicePrinters as $index => $device)
                                            <tr class="hover:bg-gray-50">
                                                <td class="border border-gray-200 px-3 py-2 text-center">{{ $index + 1 }}
                                                </td>
                                                <td class="border border-gray-200 px-3 py-2 font-medium">
                                                    {{ $device->nama_perangkat }}</td>
                                                <td class="border border-gray-200 px-3 py-2 text-center">
                                                    <span
                                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $device->jenis == 'Printer' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                        {{ $device->jenis }}
                                                    </span>
                                                </td>
                                                <td class="border border-gray-200 px-3 py-2 text-center">
                                                    {{ $device->merk_type ?? '-' }}
                                                </td>
                                                <td class="border border-gray-200 px-3 py-2 text-center">
                                                    @if ($device->kondisi == 'Baik')
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Baik</span>
                                                    @elseif($device->kondisi == 'Rusak Ringan')
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Rusak
                                                            Ringan</span>
                                                    @elseif($device->kondisi == 'Rusak Berat')
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rusak
                                                            Berat</span>
                                                    @else
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $device->kondisi ?? 'Baik' }}</span>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-200 px-3 py-2">
                                                    {{ $device->keterangan ?? '-' }}</td>
                                                <td class="border border-gray-200 px-3 py-2 text-center">
                                                    @if ($device->foto)
                                                        <button type="button"
                                                            onclick="openPhotoModal('{{ asset('storage/' . $device->foto) }}', '{{ $device->nama_perangkat }}')"
                                                            class="text-indigo-600 hover:text-indigo-800 text-sm mr-2"
                                                            style="background: none; border: none; cursor: pointer; padding: 0;"
                                                            title="Lihat Foto">
                                                            <i class="fa-solid fa-eye" style="font-size: 14px;"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button"
                                                        onclick="openEditModal({{ $device->id }}, '{{ addslashes($device->nama_perangkat) }}', '{{ $device->kondisi }}', '{{ addslashes($device->keterangan ?? '') }}')"
                                                        class="text-amber-600 hover:text-amber-800 mr-2"
                                                        style="background: none; border: none; cursor: pointer; padding: 0;"
                                                        title="Edit">
                                                        <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                                                    </button>
                                                    <x-button.action
                                                        href="{{ route('hardware.device-printer.destroy', $device->id) }}"
                                                        icon="trash" color="red" type="button" method="DELETE"
                                                        title="Hapus" />
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7"
                                                    class="border border-gray-200 px-3 py-4 text-center text-slate-500">
                                                    Belum ada device/printer yang ditambahkan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('hardware.reports') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
        <div id="addModal" tabindex="-1" aria-hidden="true" aria-labelledby="modalTitle" role="dialog"
            class="modal-overlay hidden justify-center"
            style="
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.5);
            padding: 2rem 1rem;
            overflow-y: auto;
            align-items: flex-start;
         ">

            {{-- Dialog container --}}
            <div class="relative w-full" style="max-width: 480px; margin: 1.5rem auto;">
                <div
                    style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                overflow: hidden;
            ">

                    {{-- ── Header ── --}}
                    <div
                        style="
                    background: #7664E4;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                ">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div
                                style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                                <i class="fas fa-plug" style="color: #fff; font-size: 13px;"></i>
                            </div>
                            <div>
                                <h3 id="modalTitle"
                                    style="
                                margin: 0;
                                font-size: 14px;
                                font-weight: 700;
                                color: #fff;
                                line-height: 1.2;
                            ">
                                    Tambah Device &amp; Printer</h3>
                                <p
                                    style="
                                margin: 0;
                                font-size: 11px;
                                color: rgba(255,255,255,0.7);
                                margin-top: 1px;
                            ">
                                    PC: {{ $pcData->nama_pc }} &mdash; {{ $pcData->ip }}</p>
                            </div>
                        </div>
                        <button type="button" onclick="closeModal()" aria-label="Tutup modal"
                            style="
                            width: 30px; height: 30px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.15);
                            border: none;
                            cursor: pointer;
                            display: flex; align-items: center; justify-content: center;
                            transition: background 0.15s;
                            flex-shrink: 0;
                        "
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                        </button>
                    </div>

                    {{-- ── Body ── --}}
                    <form action="{{ route('hardware.device-printer.store', $pcData->ip) }}" method="POST"
                        enctype="multipart/form-data" style="padding: 1.375rem 1.25rem 1.25rem;">
                        @csrf

                        {{-- Nama Perangkat --}}
                        <div style="margin-bottom: 1rem;">
                            <label for="nama_perangkat"
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                                Nama Perangkat <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text" id="nama_perangkat" name="nama_perangkat"
                                placeholder="Contoh: Printer Epson L3110" required value="{{ old('nama_perangkat') }}"
                                style="
                                width: 100%;
                                box-sizing: border-box;
                                height: 38px;
                                padding: 0 11px;
                                font-size: 13.5px;
                                color: #1e293b;
                                background: #f8fafc;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                outline: none;
                                transition: border-color 0.15s, box-shadow 0.15s;
                            "
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                        </div>

                        {{-- Jenis + Kondisi (2 kolom) --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 1rem;">

                            {{-- Jenis --}}
                            <div>
                                <label for="jenis"
                                    style="
                                       display: block;
                                       font-size: 11px;
                                       font-weight: 700;
                                       color: #475569;
                                       text-transform: uppercase;
                                       letter-spacing: 0.06em;
                                       margin-bottom: 5px;
                                   ">
                                    Jenis <span style="color: #ef4444;">*</span>
                                </label>
                                <div style="position: relative;">
                                    <select id="jenis" name="jenis" required
                                        style="
                                        width: 100%;
                                        box-sizing: border-box;
                                        height: 38px;
                                        padding: 0 32px 0 11px;
                                        font-size: 13.5px;
                                        color: #1e293b;
                                        background: #f8fafc;
                                        border: 1px solid #cbd5e1;
                                        border-radius: 8px;
                                        outline: none;
                                        appearance: none;
                                        -webkit-appearance: none;
                                        cursor: pointer;
                                        transition: border-color 0.15s, box-shadow 0.15s;
                                    "
                                        onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                        onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                                        <option value="">Pilih jenis</option>
                                        <option value="Printer" {{ old('jenis') == 'Printer' ? 'selected' : '' }}>Printer
                                        </option>
                                        <option value="Scanner" {{ old('jenis') == 'Scanner' ? 'selected' : '' }}>Scanner
                                        </option>
                                        <option value="Webcam" {{ old('jenis') == 'Webcam' ? 'selected' : '' }}>Webcam
                                        </option>
                                        <option value="UPS" {{ old('jenis') == 'UPS' ? 'selected' : '' }}>UPS</option>
                                        <option value="Keyboard" {{ old('jenis') == 'Keyboard' ? 'selected' : '' }}>Keyboard
                                        </option>
                                        <option value="Mouse" {{ old('jenis') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                        <option value="Monitor" {{ old('jenis') == 'Monitor' ? 'selected' : '' }}>Monitor
                                        </option>
                                        <option value="Lainnya" {{ old('jenis') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                        </option>
                                    </select>
                                    {{-- Custom chevron icon --}}
                                    <span
                                        style="
                                    position: absolute;
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    pointer-events: none;
                                    color: #94a3b8;
                                    font-size: 11px;
                                ">&#9660;</span>
                                </div>
                            </div>

                            {{-- Status / Kondisi --}}
                            <div>
                                <label for="kondisi"
                                    style="
                                       display: block;
                                       font-size: 11px;
                                       font-weight: 700;
                                       color: #475569;
                                       text-transform: uppercase;
                                       letter-spacing: 0.06em;
                                       margin-bottom: 5px;
                                   ">
                                    Kondisi
                                </label>
                                <div style="position: relative;">
                                    <select id="kondisi" name="kondisi"
                                        style="
                                        width: 100%;
                                        box-sizing: border-box;
                                        height: 38px;
                                        padding: 0 32px 0 11px;
                                        font-size: 13.5px;
                                        color: #1e293b;
                                        background: #f8fafc;
                                        border: 1px solid #cbd5e1;
                                        border-radius: 8px;
                                        outline: none;
                                        appearance: none;
                                        -webkit-appearance: none;
                                        cursor: pointer;
                                        transition: border-color 0.15s, box-shadow 0.15s;
                                    "
                                        onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                        onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                                        <option value="Baik" {{ old('kondisi', 'Baik') == 'Baik' ? 'selected' : '' }}>Baik
                                        </option>
                                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>
                                            Rusak Ringan
                                        </option>
                                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>
                                            Rusak Berat
                                        </option>
                                    </select>
                                    <span
                                        style="
                                    position: absolute;
                                    right: 10px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    pointer-events: none;
                                    color: #94a3b8;
                                    font-size: 11px;
                                ">&#9660;</span>
                                </div>
                            </div>
                        </div>

                        {{-- Merk / Type --}}
                        <div id="merk_type_group" style="margin-bottom: 1rem; display: none;">
                            <label for="merk_type_select"
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                                Merk / Type <span style="color: #ef4444;">*</span>
                            </label>

                            {{-- Select Dropdown --}}
                            <div id="merk_type_select_container"
                                style="position: relative; display: block; margin-bottom: 8px;">
                                <select id="merk_type_select"
                                    style="
                                    width: 100%;
                                    box-sizing: border-box;
                                    height: 38px;
                                    padding: 0 32px 0 11px;
                                    font-size: 13.5px;
                                    color: #1e293b;
                                    background: #f8fafc;
                                    border: 1px solid #cbd5e1;
                                    border-radius: 8px;
                                    outline: none;
                                    appearance: none;
                                    -webkit-appearance: none;
                                    cursor: pointer;
                                    transition: border-color 0.15s, box-shadow 0.15s;
                                "
                                    onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                    onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                                    <option value="">Pilih Merk/Type</option>
                                </select>
                                <span
                                    style="
                                position: absolute;
                                right: 10px;
                                top: 50%;
                                transform: translateY(-50%);
                                pointer-events: none;
                                color: #94a3b8;
                                font-size: 11px;
                            ">&#9660;</span>
                            </div>

                            {{-- Text Input for Manual Entry --}}
                            <div id="merk_type_input_container" style="display: none;">
                                <input type="text" id="merk_type_input" placeholder="Tulis Merk/Type"
                                    style="
                                    width: 100%;
                                    box-sizing: border-box;
                                    height: 38px;
                                    padding: 0 11px;
                                    font-size: 13.5px;
                                    color: #1e293b;
                                    background: #f8fafc;
                                    border: 1px solid #cbd5e1;
                                    border-radius: 8px;
                                    outline: none;
                                    transition: border-color 0.15s, box-shadow 0.15s;
                                "
                                    onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                    onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'" />
                            </div>

                            {{-- Hidden input that actually gets submitted --}}
                            <input type="hidden" id="merk_type" name="merk_type" />
                        </div>

                        {{-- Keterangan --}}
                        <div style="margin-bottom: 1.375rem;">
                            <label for="keterangan"
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                                Keterangan / Spesifikasi
                            </label>
                            <textarea id="keterangan" name="keterangan" rows="3" placeholder="Keterangan tambahan, nomor seri, dll..."
                                style="
                                width: 100%;
                                box-sizing: border-box;
                                padding: 9px 11px;
                                font-size: 13.5px;
                                color: #1e293b;
                                background: #f8fafc;
                                border: 1px solid #cbd5e1;
                                border-radius: 8px;
                                outline: none;
                                resize: vertical;
                                font-family: inherit;
                                line-height: 1.55;
                                min-height: 78px;
                                transition: border-color 0.15s, box-shadow 0.15s;
                            "
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">{{ old('keterangan') }}</textarea>
                        </div>

                        {{-- Dokumentasi --}}
                        <div style="margin-bottom: 1.375rem;">
                            <label
                                style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                                Dokumentasi
                            </label>
                            <div style="position: relative;">
                                <label for="foto"
                                    style="
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    width: 100%;
                                    height: 100px;
                                    border: 2px dashed #cbd5e1;
                                    border-radius: 10px;
                                    background: #f8fafc;
                                    cursor: pointer;
                                    transition: border-color 0.15s, background-color 0.15s;
                                "
                                    onmouseover="this.style.borderColor='#7664E4'; this.style.backgroundColor='#f1f0fd';"
                                    onmouseout="this.style.borderColor='#cbd5e1'; this.style.backgroundColor='#f8fafc';">
                                    <div
                                        style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 10px; text-align: center; pointer-events: none;">
                                        <i class="fas fa-cloud-upload-alt"
                                            style="font-size: 24px; color: #7664E4; margin-bottom: 6px;"></i>
                                        <span id="file_upload_label"
                                            style="font-size: 12px; font-weight: 600; color: #475569;">Pilih atau tarik foto ke
                                            sini</span>
                                        <span style="font-size: 10px; color: #94a3b8; margin-top: 2px;">Format JPG, PNG, JPEG
                                            (Max. 2MB)</span>
                                    </div>
                                    <input type="file" id="foto" name="foto" accept="image/*"
                                        style="display: none;" onchange="handleFileSelected(this)" />
                                </label>
                            </div>
                        </div>

                        {{-- ── Footer / Action buttons ── --}}
                        <div
                            style="
                        border-top: 1px solid #f1f5f9;
                        padding-top: 1rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 8px;
                    ">
                            <button type="button" onclick="closeModal()"
                                style="
                                height: 38px;
                                padding: 0 16px;
                                font-size: 13px;
                                font-weight: 600;
                                color: #64748b;
                                background: #f1f5f9;
                                border: 1px solid #e2e8f0;
                                border-radius: 8px;
                                cursor: pointer;
                                transition: background 0.15s;
                            "
                                onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                Batal
                            </button>
                            <button type="submit"
                                style="
                                height: 38px;
                                padding: 0 20px;
                                font-size: 13px;
                                font-weight: 700;
                                color: #fff;
                                background: #7664E4;
                                border: none;
                                border-radius: 8px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 7px;
                                transition: background 0.15s, box-shadow 0.15s;
                            "
                                onmouseover="this.style.background='#6453d4'; this.style.boxShadow='0 4px 12px rgba(118,100,228,0.35)'"
                                onmouseout="this.style.background='#7664E4'; this.style.boxShadow='none'">
                                <i class="fas fa-save" style="font-size: 12px;"></i>
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Photo Preview Modal -->
        <div id="photoModal" tabindex="-1" aria-hidden="true" aria-labelledby="photoModalTitle" role="dialog"
            class="modal-overlay hidden items-center justify-center"
            style="
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(0,0,0,0.5);
            padding: 1rem;
         ">

            {{-- Dialog container --}}
            <div class="relative w-full" style="max-width: 640px; margin: auto;">
                <div
                    style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                overflow: hidden;
            ">

                    {{-- Header --}}
                    <div
                        style="
                    background: #7664E4;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                ">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div
                                style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                                <i class="fas fa-image" style="color: #fff; font-size: 13px;"></i>
                            </div>
                            <div>
                                <h3 id="photoModalTitle"
                                    style="
                                    margin: 0;
                                    font-size: 14px;
                                    font-weight: 700;
                                    color: #fff;
                                    line-height: 1.2;
                                ">
                                    Dokumentasi</h3>
                            </div>
                        </div>
                        <button type="button" onclick="closePhotoModal()" aria-label="Tutup modal"
                            style="
                            width: 30px; height: 30px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.15);
                            border: none;
                            cursor: pointer;
                            display: flex; align-items: center; justify-content: center;
                            transition: background 0.15s;
                            flex-shrink: 0;
                        "
                            onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div style="padding: 1.25rem; text-align: center; background: #f8fafc;">
                        <img id="photoPreviewImg" src="" alt="Dokumentasi"
                            style="max-width: 100%; max-height: 480px; border-radius: 8px; object-fit: contain; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" />
                    </div>

                    {{-- Footer --}}
                    <div
                        style="
                        border-top: 1px solid #f1f5f9;
                        padding: 1rem 1.25rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        background: #ffffff;
                    ">
                        <button type="button" onclick="closePhotoModal()"
                            style="
                            height: 38px;
                            padding: 0 20px;
                            font-size: 13px;
                            font-weight: 600;
                            color: #fff;
                            background: #7664E4;
                            border: none;
                            border-radius: 8px;
                            cursor: pointer;
                            transition: background 0.15s;
                        "
                            onmouseover="this.style.background='#6453d4'" onmouseout="this.style.background='#7664E4'">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endpush
@endsection

{{-- =============================================================== --}}
{{-- Modal Edit Device/Printer                                        --}}
{{-- =============================================================== --}}
@push('modals')
    <div id="editModal" tabindex="-1" aria-hidden="true" aria-labelledby="editModalTitle" role="dialog"
        class="modal-overlay hidden justify-center"
        style="
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.5);
        padding: 2rem 1rem;
        overflow-y: auto;
        align-items: flex-start;
     ">
        <div class="relative w-full" style="max-width: 480px; margin: 1.5rem auto;">
            <div style="background: #ffffff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); overflow: hidden;">

                {{-- Header --}}
                <div style="background: #7664E4; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.18); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <i class="fas fa-pen" style="color: #fff; font-size: 13px;"></i>
                        </div>
                        <div>
                            <h3 id="editModalTitle" style="margin: 0; font-size: 14px; font-weight: 700; color: #fff; line-height: 1.2;">Edit Device &amp; Printer</h3>
                            <p id="editModalSubtitle" style="margin: 0; font-size: 11px; color: rgba(255,255,255,0.8); margin-top: 1px;"></p>
                        </div>
                    </div>
                    <button type="button" onclick="closeEditModal()" aria-label="Tutup modal"
                        style="width: 30px; height: 30px; border-radius: 8px; background: rgba(255,255,255,0.15); border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.15s; flex-shrink: 0;"
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                    </button>
                </div>

                {{-- Form --}}
                <form id="editForm" action="" method="POST" style="padding: 1.375rem 1.25rem 1.25rem;">
                    @csrf
                    @method('PUT')

                    {{-- Kondisi --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="edit_kondisi" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 5px;">
                            Kondisi <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <select id="edit_kondisi" name="kondisi"
                                style="width: 100%; box-sizing: border-box; height: 38px; padding: 0 32px 0 11px; font-size: 13.5px; color: #1e293b; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; appearance: none; -webkit-appearance: none; cursor: pointer; transition: border-color 0.15s, box-shadow 0.15s;"
                                onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                                onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                            <span style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94a3b8; font-size: 11px;">&#9660;</span>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div style="margin-bottom: 1.375rem;">
                        <label for="edit_keterangan" style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 5px;">
                            Keterangan / Spesifikasi
                        </label>
                        <textarea id="edit_keterangan" name="keterangan" rows="4"
                            placeholder="Keterangan tambahan, nomor seri, dll..."
                            style="width: 100%; box-sizing: border-box; padding: 9px 11px; font-size: 13.5px; color: #1e293b; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; resize: vertical; font-family: inherit; line-height: 1.55; min-height: 90px; transition: border-color 0.15s, box-shadow 0.15s;"
                            onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'"
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"></textarea>
                    </div>

                    {{-- Footer Buttons --}}
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
                        <button type="button" onclick="closeEditModal()"
                            style="height: 38px; padding: 0 16px; font-size: 13px; font-weight: 600; color: #64748b; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: background 0.15s;"
                            onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            Batal
                        </button>
                        <button type="submit"
                            style="height: 38px; padding: 0 20px; font-size: 13px; font-weight: 700; color: #fff; background: #7664E4; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 7px; transition: background 0.15s, box-shadow 0.15s;"
                            onmouseover="this.style.background='#6453d4'; this.style.boxShadow='0 4px 12px rgba(118,100,228,0.35)'" onmouseout="this.style.background='#7664E4'; this.style.boxShadow='none'">
                            <i class="fas fa-save" style="font-size: 12px;"></i>
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
    <script>
        function openModal() {
            const modal = document.getElementById('addModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Fokus ke input pertama agar aksesibel
            setTimeout(() => {
                const firstInput = modal.querySelector('input, select, textarea');
                if (firstInput) firstInput.focus();
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('addModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';

            // Reset custom file input labels and inputs
            const label = document.getElementById('file_upload_label');
            if (label) {
                label.innerText = 'Pilih atau tarik foto ke sini';
                label.style.color = '#475569';
            }
            const fileInput = document.getElementById('foto');
            if (fileInput) fileInput.value = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('addModal');
            if (!modal) return;

            // Pindahkan modal ke body agar tidak terpengaruh stacking context parent
            document.body.appendChild(modal);

            // Tutup saat klik backdrop
            modal.addEventListener('click', function(e) {
                if (e.target === this) closeModal();
            });

            // Dynamic Merk/Type Selection
            const jenisSelect = document.getElementById('jenis');
            const merkTypeGroup = document.getElementById('merk_type_group');
            const merkTypeSelectContainer = document.getElementById('merk_type_select_container');
            const merkTypeSelect = document.getElementById('merk_type_select');
            const merkTypeInputContainer = document.getElementById('merk_type_input_container');
            const merkTypeInput = document.getElementById('merk_type_input');
            const merkTypeHidden = document.getElementById('merk_type');

            const merkOptions = {
                'Printer': [
                    'Brother HL-L2360DN',
                    'Brother MFC-L5900DW',
                    'Brother MFC-T4500DW',
                    'Epson L6550',
                    'Epson L6490',
                    'Epson L4150',
                    'Epson TM-T82',
                    'Epson LX-310',
                    'Epson M1140',
                    'Zebra ZD220',
                    'Zebra ZD230'
                ],
                'Scanner': [
                    'Zebra DS9308',
                    'Epson DS-410',
                    'Canon Lide 300',
                    'Canon Lide 400'
                ],
                'Webcam': [
                    'Logitech C270'
                ],
                'UPS': [
                    'APC BVX700LUI-MS'
                ],
                'Keyboard': [
                    'Logitech K120'
                ],
                'Mouse': [
                    'Logitech M90'
                ],
                'Monitor': [
                    'LG 20MK400H'
                ],
                'Lainnya': [
                    'M-Tech',
                    'Vention',
                    'Gaintech',
                    'Huion Inspiroy RTS-300',
                    'Digital Persona 4500',
                    'TP-LINK TL-SF1005D',
                    'TP-LINK TL-WN725',
                    'WD'
                ]
            };

            function updateMerkTypeFields() {
                const selectedJenis = jenisSelect.value;

                if (!selectedJenis) {
                    merkTypeGroup.style.display = 'none';
                    merkTypeHidden.value = '';
                    return;
                }

                merkTypeGroup.style.display = 'block';

                if (merkOptions[selectedJenis]) {
                    // Populate select
                    merkTypeSelect.innerHTML = '<option value="">Pilih Merk/Type</option>';
                    merkOptions[selectedJenis].forEach(opt => {
                        const option = document.createElement('option');
                        option.value = opt;
                        option.textContent = opt;
                        merkTypeSelect.appendChild(option);
                    });
                    // Add manual option
                    const manualOption = document.createElement('option');
                    manualOption.value = '__manual__';
                    manualOption.textContent = 'Lainnya';
                    merkTypeSelect.appendChild(manualOption);

                    merkTypeSelectContainer.style.display = 'block';
                    merkTypeSelect.required = true;

                    // Toggle manual input based on selection
                    if (merkTypeSelect.value === '__manual__') {
                        merkTypeInputContainer.style.display = 'block';
                        merkTypeInput.required = true;
                    } else {
                        merkTypeInputContainer.style.display = 'none';
                        merkTypeInput.required = false;
                    }
                } else {
                    // No pre-defined options (e.g. Keyboard, Mouse, Monitor)
                    merkTypeSelectContainer.style.display = 'none';
                    merkTypeSelect.required = false;

                    merkTypeInputContainer.style.display = 'block';
                    merkTypeInput.required = true;
                }

                syncHiddenValue();
            }

            function syncHiddenValue() {
                const selectedJenis = jenisSelect.value;
                if (merkOptions[selectedJenis]) {
                    if (merkTypeSelect.value === '__manual__') {
                        merkTypeHidden.value = merkTypeInput.value;
                    } else {
                        merkTypeHidden.value = merkTypeSelect.value;
                    }
                } else {
                    merkTypeHidden.value = merkTypeInput.value;
                }
            }

            jenisSelect.addEventListener('change', function() {
                merkTypeSelect.value = '';
                merkTypeInput.value = '';
                updateMerkTypeFields();
            });

            merkTypeSelect.addEventListener('change', function() {
                if (this.value === '__manual__') {
                    merkTypeInputContainer.style.display = 'block';
                    merkTypeInput.required = true;
                    merkTypeInput.focus();
                } else {
                    merkTypeInputContainer.style.display = 'none';
                    merkTypeInput.required = false;
                }
                syncHiddenValue();
            });

            merkTypeInput.addEventListener('input', syncHiddenValue);

            const photoModal = document.getElementById('photoModal');
            if (photoModal) {
                document.body.appendChild(photoModal);
                photoModal.addEventListener('click', function(e) {
                    if (e.target === this) closePhotoModal();
                });
            }
        });

        function openPhotoModal(imgUrl, title) {
            const modal = document.getElementById('photoModal');
            const img = document.getElementById('photoPreviewImg');
            const titleElem = document.getElementById('photoModalTitle');
            if (!modal || !img) return;

            img.src = imgUrl;
            if (titleElem) {
                titleElem.innerText = 'Dokumentasi - ' + title;
            }
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePhotoModal() {
            const modal = document.getElementById('photoModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        function handleFileSelected(input) {
            const label = document.getElementById('file_upload_label');
            if (label && input.files && input.files[0]) {
                label.innerText = 'Foto terpilih: ' + input.files[0].name;
                label.style.color = '#7664E4';
            } else if (label) {
                label.innerText = 'Pilih atau tarik foto ke sini';
                label.style.color = '#475569';
            }
        }

        function openEditModal(id, namaPerangkat, kondisi, keterangan) {
            const modal = document.getElementById('editModal');
            if (!modal) return;

            // Set action form
            const form = document.getElementById('editForm');
            if (form) {
                form.action = '{{ url("hardware/device-printer") }}/' + id;
            }

            // Set subtitle
            const subtitle = document.getElementById('editModalSubtitle');
            if (subtitle) subtitle.innerText = namaPerangkat;

            // Set nilai kondisi
            const kondisiSelect = document.getElementById('edit_kondisi');
            if (kondisiSelect) kondisiSelect.value = kondisi || 'Baik';

            // Set nilai keterangan
            const keteranganArea = document.getElementById('edit_keterangan');
            if (keteranganArea) keteranganArea.value = keterangan || '';

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        // Append editModal ke body dan pasang event backdrop
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editModal');
            if (editModal) {
                document.body.appendChild(editModal);
                editModal.addEventListener('click', function(e) {
                    if (e.target === this) closeEditModal();
                });
            }
        });

        // Tutup dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closePhotoModal();
                closeEditModal();
            }
        });
    </script>
@endpush
