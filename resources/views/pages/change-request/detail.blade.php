@extends('layouts.app')

@section('title', 'SIGERCEP - Detail Change Request')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Change Request</h6>
                    </div>
                    <div class="flex-auto p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- Nama --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Nama</label>
                                <p class="text-slate-600">{{ ucfirst($changeRequest->nama) }}</p>
                            </div>

                            {{-- Jabatan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Jabatan</label>
                                <p class="text-slate-600">
                                    {{ $changeRequest->user->jabatan ?? ($changeRequest->jabatan ?? '-') }}</p>
                            </div>

                            {{-- Permintaan Fitur --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Permintaan Fitur</label>
                                <p class="text-slate-600">{{ $changeRequest->permintaan_fitur ?? '-' }}</p>
                            </div>

                            {{-- Tanggal Permintaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Permintaan</label>
                                <p class="text-slate-600">
                                    {{ \Carbon\Carbon::parse($changeRequest->created_at)->translatedFormat('d F Y') }}</p>
                            </div>

                            {{-- Status Dokumen --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status Dokumen</label>
                                @php
                                    $sdColor = match ($changeRequest->status_dokumen ?? 'Dalam Proses') {
                                        'Terpenuhi' => 'background-color:#b3e5fc; color:#01579b;',
                                        'Dalam Proses' => 'background-color:#ffe0b2; color:#e65100;',
                                        'Tidak Ada' => 'background-color:#ffcdd2; color:#b71c1c;',
                                        default => 'background-color:#e0e0e0; color:#333;',
                                    };
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full" style="{{ $sdColor }}">
                                    {{ $changeRequest->status_dokumen ?? 'Dalam Proses' }}
                                </span>
                            </div>

                            {{-- Status Pengerjaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Status Pengerjaan</label>
                                @php
                                    $spColor = match ($changeRequest->status_pengerjaan ?? 'Open') {
                                        'Done' => 'background-color:#0b5394; color:#ffffff;',
                                        'In Progress' => 'background-color:#fce5cd; color:#783f04;',
                                        'Open' => 'background-color:#d9ead3; color:#274e13;',
                                        'Closed' => 'background-color:#4c382b; color:#ffffff;',
                                        'Pending' => 'background-color:#674ea7; color:#ffffff;',
                                        'QC' => 'background-color:#134f5c; color:#ffffff;',
                                        default => 'background-color:#95a5a6; color:#ffffff;',
                                    };
                                @endphp
                                <span class="px-3 py-1 text-xs font-semibold rounded-full" style="{{ $spColor }}">
                                    {{ $changeRequest->status_pengerjaan ?? 'Open' }}
                                </span>
                            </div>

                            {{-- No Tiket --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">No Tiket</label>
                                @if (!empty($changeRequest->no_tiket) && $changeRequest->no_tiket !== 'No Tiket')
                                    <p class="text-slate-600">
                                        {{ \Illuminate\Support\Str::startsWith($changeRequest->no_tiket, '#') ? $changeRequest->no_tiket : '#' . $changeRequest->no_tiket }}
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400" style="font-style: italic !important;">#No Tiket</p>
                                @endif
                            </div>

                            {{-- Deskripsi --}}
                            <div class="md:col-span-2">
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Deskripsi</label>
                                <p class="text-slate-600 whitespace-pre-line">{{ $changeRequest->deskripsi }}</p>
                            </div>

                            {{-- File Pendukung --}}
                            @if ($changeRequest->file_path)
                                <div>
                                    <label class="block mb-1 text-sm font-semibold text-slate-700">File Pendukung</label>
                                    <a href="{{ route('change-request.show-file', $changeRequest->id) }}" target="_blank"
                                        class="px-2 py-1 bg-blue-500 rounded text-white hover:shadow-xs active:opacity-85">
                                        📄 Lihat File PDF
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('change-request.index') }}"
                                class="inline-block px-6 py-2 text-xs font-semibold text-slate-700 uppercase bg-gray-200 rounded-lg shadow-md hover:shadow-xs active:opacity-85">
                                Kembali
                            </a>
                        </div>

                        {{-- Approval 2 Tahap --}}
                        <div class="mt-8 rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                <h6 class="font-bold text-sm text-slate-700"><i class="fas fa-check-double mr-1"></i> Persetujuan</h6>
                                @php
                                    $approvalTotal = ($changeRequest->approval_1_status ?? 'Menunggu') . ' / ' . ($changeRequest->approval_2_status ?? 'Menunggu');
                                @endphp
                                <span class="text-xs font-semibold text-slate-500">{{ $approvalTotal }}</span>
                            </div>

                            <div class="p-4 space-y-4">
                                @php
                                    $badgeColor = function ($s) {
                                        return match ($s) {
                                            'Disetujui' => 'background-color:#d1fae5; color:#065f46;',
                                            'Ditolak' => 'background-color:#fee2e2; color:#991b1b;',
                                            default => 'background-color:#fef3c7; color:#92400e;',
                                        };
                                    };
                                    $stages = [
                                        'approval_1' => 'Tahap 1 (Atasan Langsung)',
                                        'approval_2' => 'Tahap 2 (' . config('approvals.stage2_jabatan') . ')',
                                    ];
                                @endphp

                                @foreach ($stages as $field => $label)
                                    @php
                                        $status = $changeRequest->{$field . '_status'} ?? 'Menunggu';
                                    @endphp
                                    <div class="rounded-lg border border-gray-100 p-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-semibold text-slate-600 uppercase tracking-wide">{{ $label }}</span>
                                            <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full" style="{{ $badgeColor($status) }}">
                                                {{ $status }}
                                            </span>
                                        </div>
                                        @if ($changeRequest->{$field . '_at'})
                                            <div class="mt-2 text-xs text-slate-500">
                                                Oleh <b>{{ $changeRequest->{$field . '_by'} ?? '-' }}</b>
                                                • {{ \Carbon\Carbon::parse($changeRequest->{$field . '_at'})->translatedFormat('d F Y H:i') }}
                                            </div>
                                        @endif
                                        @if ($changeRequest->{$field . '_note'})
                                            <div class="mt-1 text-xs text-slate-600 italic">"{{ $changeRequest->{$field . '_note'} }}"</div>
                                        @endif
                                    </div>
                                @endforeach

                                @if ($approvableLevel > 0)
                                    <form action="{{ route('change-request.approve', $changeRequest->id) }}" method="POST"
                                        class="mt-4 rounded-lg bg-indigo-50 border border-indigo-100 p-3"
                                        onsubmit="return confirm('Simpan persetujuan ini?')">
                                        @csrf
                                        <input type="hidden" name="decision" value="" id="approvalDecision">
                                        <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">
                                            <i class="fas fa-user-check mr-1"></i> Anda menyetujui tahap ini
                                        </p>
                                        <textarea name="note" rows="2" required placeholder="Catatan (wajib diisi)..." maxlength="500"
                                            class="w-full px-3 py-2 border border-indigo-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                                        <div class="mt-2 flex gap-2">
                                            <button type="submit" onclick="document.getElementById('approvalDecision').value='Disetujui'"
                                                class="px-4 py-2 text-xs font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700">
                                                <i class="fas fa-check mr-1"></i> Setujui
                                            </button>
                                            <button type="submit" onclick="document.getElementById('approvalDecision').value='Ditolak'"
                                                class="px-4 py-2 text-xs font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
