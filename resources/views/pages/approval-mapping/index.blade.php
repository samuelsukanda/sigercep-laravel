@extends('layouts.app')

@section('title', 'SIGERCEP - Atasan Langsung')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3>Atasan Langsung (Approval Change Request)</h3>
                </div>

                @if (session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-semibold"
                        style="background-color:#d1fae5; color:#065f46;">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 px-4 py-3 rounded-lg text-sm font-semibold"
                        style="background-color:#fee2e2; color:#991b1b;">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl mb-4">
                    <div class="flex-auto p-6">
                        <div class="mb-4 rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3">
                            <p class="text-sm text-indigo-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                Hanya jabatan yang terdaftar sebagai <b>peminta</b> yang dapat mengajukan Change Request.
                                Peminta diapprove atasan langsungnya (Tahap 1), lalu <b>{{ $stage2 }}</b> (Tahap 2).
                                Ganti atasan langsung pada tabel di bawah ini.
                            </p>
                        </div>

                        {{-- Form Tambah --}}
                        <form action="{{ route('approval-mapping.store') }}" method="POST"
                            class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <div class="flex flex-col" style="min-width:200px; flex:1 1 220px;">
                                <label class="text-xs font-semibold text-gray-600 mb-1.5">Jabatan Peminta</label>
                                <input type="text" name="requester_jabatan" required list="jabatanList"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Contoh: SPV Keuangan">
                            </div>
                            <div class="flex flex-col" style="min-width:200px; flex:1 1 220px;">
                                <label class="text-xs font-semibold text-gray-600 mb-1.5">Atasan Langsung (Tahap 1)</label>
                                <input type="text" name="approver_jabatan" required list="jabatanList"
                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="Contoh: Manajer Keuangan">
                            </div>
                            <button type="submit"
                                class="h-9 px-4 text-xs font-semibold text-white rounded-lg shadow-md hover:shadow-sm active:opacity-85"
                                style="background-color:#7664E4 !important;">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </button>
                        </form>

                        <datalist id="jabatanList">
                            @foreach ($jabatanList as $j)
                                <option value="{{ $j }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                {{-- Daftar Mapping --}}
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="flex-auto p-6">
                        @if ($mappings->isEmpty())
                            <p class="text-sm text-slate-500 text-center py-6">Belum ada mapping atasan langsung.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-xs uppercase text-slate-500 border-b border-gray-200">
                                            <th class="px-4 py-3">Jabatan Peminta</th>
                                            <th class="px-4 py-3">Atasan Langsung (Tahap 1)</th>
                                            <th class="px-4 py-3 text-center">Tahap 2</th>
                                            <th class="px-4 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mappings as $mapping)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <form action="{{ route('approval-mapping.update', $mapping->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <td class="px-4 py-2">
                                                        <input type="text" name="requester_jabatan" value="{{ $mapping->requester_jabatan }}"
                                                            required list="jabatanList"
                                                            class="w-full px-2 py-1.5 text-sm border border-transparent rounded-lg hover:border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        <input type="text" name="approver_jabatan" value="{{ $mapping->approver_jabatan }}"
                                                            required list="jabatanList"
                                                            class="w-full px-2 py-1.5 text-sm border border-transparent rounded-lg hover:border-gray-300 focus:ring-2 focus:ring-indigo-500">
                                                    </td>
                                                    <td class="px-4 py-2 text-center text-xs font-semibold text-slate-500">{{ $stage2 }}</td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <button type="submit" title="Simpan"
                                                                class="text-green-600 hover:text-green-700 transition">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                </form>
                                                <form action="{{ route('approval-mapping.destroy', $mapping->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus mapping ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus"
                                                        class="text-red-500 hover:text-red-700 transition">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection