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

                        {{-- Approval 2 Tahap --}}
                        <div class="mt-8 rounded-xl border border-gray-200 overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                <h6 class="font-bold text-sm text-slate-700"><i class="fas fa-check-double mr-1"></i>
                                    Persetujuan</h6>
                                @php
                                    $approvalTotal =
                                        ($changeRequest->approval_1_status ?? 'Menunggu') .
                                        ' / ' .
                                        ($changeRequest->approval_2_status ?? 'Menunggu');
                                @endphp
                                <span class="text-xs font-semibold text-slate-500">{{ $approvalTotal }}</span>
                            </div>

                            <div class="p-4">
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

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($stages as $field => $label)
                                        @php
                                            $status = $changeRequest->{$field . '_status'} ?? 'Menunggu';
                                        @endphp
                                        <div class="rounded-lg border border-gray-100 p-3">
                                            <div class="flex items-center justify-between">
                                                <span
                                                    class="text-xs font-semibold text-slate-600 uppercase tracking-wide">{{ $label }}</span>
                                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full"
                                                    style="{{ $badgeColor($status) }}">
                                                    {{ $status }}
                                                </span>
                                            </div>
                                            @if ($changeRequest->{$field . '_at'})
                                                <div class="mt-2 text-xs text-slate-500">
                                                    Oleh <b>{{ ucwords(str_replace('.', ' ', $changeRequest->{$field . '_by'} ?? '-')) }}</b>
                                                    •
                                                    {{ \Carbon\Carbon::parse($changeRequest->{$field . '_at'})->translatedFormat('d F Y H:i') }}
                                                </div>
                                            @endif
                                            @if ($changeRequest->{$field . '_ttd'})
                                                <div class="mt-2">
                                                    <img src="{{ $changeRequest->{$field . '_ttd'} }}" alt="Tanda tangan"
                                                        class="border rounded bg-white" style="max-height:80px;">
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                @if ($approvableLevel > 0)
                                    <form action="{{ route('change-request.approve', $changeRequest->id) }}" method="POST"
                                        id="approveForm" class="mt-4 rounded-lg bg-indigo-50 border border-indigo-100 p-3">
                                        @csrf
                                        <input type="hidden" name="decision" value="Disetujui" id="approvalDecision">
                                        <p class="text-xs font-semibold text-indigo-700 uppercase tracking-wide mb-2">
                                            <i class="fas fa-user-check mr-1"></i> Approval
                                        </p>
                                        <div class="border rounded shadow-sm bg-white p-4">
                                            <div style="width: 100%; height: 200px; position: relative;">
                                                <canvas id="approval-signature-pad" class="rounded"
                                                    style="border: 2px solid #9e9e9e; width: 100%; height: 100%; touch-action: none; display: block;"></canvas>
                                            </div>
                                            <input type="hidden" name="tanda_tangan" id="approval_tanda_tangan">
                                            <div class="mt-4 flex gap-2" style="align-items: center; flex-wrap: wrap;">
                                                <button type="submit"
                                                    class="relative mb-2 mr-1 text-white border border-solid rounded-lg bg-gradient-to-tl from-emerald-500 to-teal-400 border-emerald-300 px-4 py-2 flex items-center gap-2">
                                                    <i class="fa fa-check mr-1"></i> Approve
                                                </button>
                                                <button type="button" id="approval-undo"
                                                    class="relative mb-2 mr-1 text-white border border-solid rounded-lg bg-gradient-to-tl from-zinc-800 to-zinc-700 border-slate-100 px-4 py-2 flex items-center gap-2">
                                                    <i class="fa fa-undo mr-1"></i> Undo
                                                </button>
                                                <button type="button" id="approval-clear"
                                                    class="relative mb-2 text-white border border-red-300 border-solid rounded-lg bg-gradient-to-tl from-red-600 to-orange-600 px-4 py-2 flex items-center gap-2">
                                                    <i class="fa fa-trash mr-1"></i> Clear
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="my-6">
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

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-swal-approve {
            background-color: #10b981 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-approve:hover {
            background-color: #059669 !important;
        }

        .btn-swal-cancel {
            background-color: #6b7280 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-cancel:hover {
            background-color: #4b5563 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById("approval-signature-pad");
            const form = document.getElementById("approveForm");
            const clearBtn = document.getElementById("approval-clear");
            const undoBtn = document.getElementById("approval-undo");
            const ttdInput = document.getElementById("approval_tanda_tangan");
            const decisionInput = document.getElementById("approvalDecision");

            if (!canvas || !form || !clearBtn || !undoBtn || !ttdInput) return;

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const width = canvas.offsetWidth;
                const height = canvas.offsetHeight;
                canvas.width = width * ratio;
                canvas.height = height * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }

            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            const signaturePad = new SignaturePad(canvas, {
                backgroundColor: "rgb(255,255,255)",
            });

            clearBtn.addEventListener("click", function() {
                signaturePad.clear();
            });

            undoBtn.addEventListener("click", function() {
                const data = signaturePad.toData();
                if (data.length) {
                    data.pop();
                    signaturePad.fromData(data);
                }
            });

            form.addEventListener("submit", function(e) {
                if (decisionInput.value === "Disetujui" && signaturePad.isEmpty()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanda Tangan Kosong',
                        text: 'Silakan isi tanda tangan terlebih dahulu sebelum menyetujui.',
                        customClass: {
                            confirmButton: 'btn-swal-approve'
                        },
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                e.preventDefault();
                ttdInput.value = signaturePad.isEmpty() ? '' : signaturePad.toDataURL('image/png');
                Swal.fire({
                    title: 'Konfirmasi Persetujuan',
                    text: 'Apakah Anda yakin?',
                    icon: 'question',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'btn-swal-approve',
                        cancelButton: 'btn-swal-cancel'
                    },
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Persetujuan sedang diproses...",
                            icon: "success",
                            showConfirmButton: false
                        });
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
