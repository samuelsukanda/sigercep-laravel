@extends('layouts.app')

@section('title', 'SIGERCEP - Detail Change Request')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">
                <div class="relative flex flex-col bg-white shadow-soft-xl rounded-2xl">
                    <div class="p-6 pb-0 mb-0 bg-white rounded-t-2xl">
                        <h6 class="mb-0 font-bold text-lg">Detail Change Request #{{ $changeRequest->id }}</h6>
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
                                <p class="text-slate-600">{{ $changeRequest->user->jabatan ?? $changeRequest->jabatan ?? '-' }}</p>
                            </div>

                            {{-- Tanggal Permintaan --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">Tanggal Permintaan</label>
                                <p class="text-slate-600">{{ \Carbon\Carbon::parse($changeRequest->created_at)->translatedFormat('d F Y') }}</p>
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
                                    <p class="text-slate-600">{{ \Illuminate\Support\Str::startsWith($changeRequest->no_tiket, '#') ? $changeRequest->no_tiket : '#' . $changeRequest->no_tiket }}</p>
                                @else
                                    <p class="text-xs text-slate-400" style="font-style: italic !important;">#No Tiket</p>
                                @endif
                            </div>

                            {{-- PIC Request --}}
                            <div>
                                <label class="block mb-1 text-sm font-semibold text-slate-700">PIC Request</label>
                                <p class="text-slate-600">{{ $changeRequest->pic_request ?? '-' }}</p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
