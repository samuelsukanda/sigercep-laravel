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
                                <p class="text-slate-600">{{ $pcData->unit }}</p>
                            </div>
                            {{-- Lantai --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Lantai</label>
                                <p class="text-slate-600">{{ $pcData->lantai }}</p>
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
                                                    @if($device->kondisi == 'Baik')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800">Baik</span>
                                                    @elseif($device->kondisi == 'Rusak Ringan')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Rusak Ringan</span>
                                                    @elseif($device->kondisi == 'Rusak Berat')
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rusak Berat</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $device->kondisi ?? 'Baik' }}</span>
                                                    @endif
                                                </td>
                                                <td class="border border-gray-200 px-3 py-2">
                                                    {{ $device->keterangan ?? '-' }}</td>
                                                <td class="border border-gray-200 px-3 py-2 text-center">
                                                    <x-button.action href="{{ route('hardware.device-printer.destroy', $device->id) }}" icon="trash" color="red"
                                                        type="button" method="DELETE" title="Hapus" />
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="border border-gray-200 px-3 py-4 text-center text-slate-500">
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
    <div id="addModal"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="modalTitle"
         role="dialog"
         class="modal-overlay hidden items-center justify-center"
         style="
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: transparent;
            padding: 1rem;
         ">

        {{-- Dialog container --}}
        <div class="relative w-full" style="max-width: 480px; margin: auto;">
            <div style="
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                overflow: hidden;
            ">

                {{-- ── Header ── --}}
                <div style="
                    background: #7664E4;
                    padding: 1rem 1.25rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                ">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <div style="
                            width: 32px; height: 32px;
                            border-radius: 8px;
                            background: rgba(255,255,255,0.18);
                            display: flex; align-items: center; justify-content: center;
                            flex-shrink: 0;
                        ">
                            <i class="fas fa-plug" style="color: #fff; font-size: 13px;"></i>
                        </div>
                        <div>
                            <h3 id="modalTitle" style="
                                margin: 0;
                                font-size: 14px;
                                font-weight: 700;
                                color: #fff;
                                line-height: 1.2;
                            ">Tambah Device &amp; Printer</h3>
                            <p style="
                                margin: 0;
                                font-size: 11px;
                                color: rgba(255,255,255,0.7);
                                margin-top: 1px;
                            ">PC: {{ $pcData->nama_pc }} &mdash; {{ $pcData->ip }}</p>
                        </div>
                    </div>
                    <button type="button"
                        onclick="closeModal()"
                        aria-label="Tutup modal"
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
                <form action="{{ route('hardware.device-printer.store', $pcData->ip) }}"
                      method="POST"
                      style="padding: 1.375rem 1.25rem 1.25rem;">
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
                        <input
                            type="text"
                            id="nama_perangkat"
                            name="nama_perangkat"
                            placeholder="Contoh: Printer Epson L3110"
                            required
                            value="{{ old('nama_perangkat') }}"
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
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                        />
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
                                <select
                                    id="jenis"
                                    name="jenis"
                                    required
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
                                    onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                                >
                                    <option value="">Pilih jenis</option>
                                    <option value="Printer"   {{ old('jenis') == 'Printer'  ? 'selected' : '' }}>Printer</option>
                                    <option value="Scanner"   {{ old('jenis') == 'Scanner'  ? 'selected' : '' }}>Scanner</option>
                                    <option value="Webcam"    {{ old('jenis') == 'Webcam'   ? 'selected' : '' }}>Webcam</option>
                                    <option value="Speaker"   {{ old('jenis') == 'Speaker'  ? 'selected' : '' }}>Speaker</option>
                                    <option value="UPS"       {{ old('jenis') == 'UPS'      ? 'selected' : '' }}>UPS</option>
                                    <option value="Lainnya"   {{ old('jenis') == 'Lainnya'  ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                {{-- Custom chevron icon --}}
                                <span style="
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
                                <select
                                    id="kondisi"
                                    name="kondisi"
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
                                    onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                                >
                                    <option value="Baik"         {{ old('kondisi', 'Baik') == 'Baik'        ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan'        ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat"  {{ old('kondisi') == 'Rusak Berat'         ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                <span style="
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
                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            placeholder="Keterangan tambahan, nomor seri, dll..."
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
                            onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'"
                        >{{ old('keterangan') }}</textarea>
                    </div>

                    {{-- ── Footer / Action buttons ── --}}
                    <div style="
                        border-top: 1px solid #f1f5f9;
                        padding-top: 1rem;
                        display: flex;
                        justify-content: flex-end;
                        align-items: center;
                        gap: 8px;
                    ">
                        <button type="button"
                            onclick="closeModal()"
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
                            onmouseover="this.style.background='#e2e8f0'"
                            onmouseout="this.style.background='#f1f5f9'">
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
    @endpush
@endsection

@push('scripts')
<script src="{{ asset('assets/js/alert-delete.js') }}"></script>
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
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('addModal');
        if (!modal) return;

        // Pindahkan modal ke body agar tidak terpengaruh stacking context parent
        document.body.appendChild(modal);

        // Tutup saat klik backdrop
        modal.addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    });

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush
