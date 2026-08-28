@extends('layouts.app')

@section('title', 'SIGERCEP - Approval Change Request')

@push('styles')
    <style>
        /* ===== PAGE HEADER ===== */
        .am-page-header {
            background: linear-gradient(135deg, var(--accent-grad-1) 0%, var(--accent-grad-2) 60%, var(--accent-grad-3) 100%);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
        }

        .am-page-header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 50%;
        }

        .am-page-header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 20px;
            width: 160px;
            height: 160px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .am-page-header h1 {
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 4px;
            position: relative;
            z-index: 1;
        }

        .am-page-header p {
            color: rgba(255, 255, 255, 0.78);
            font-size: 13px;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .am-header-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(4px);
        }

        /* ===== SECTION CARD ===== */
        .am-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--accent-soft);
            box-shadow: 0 4px 24px var(--accent-soft), 0 1px 4px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .am-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(to right, var(--accent-soft), transparent);
        }

        .am-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .am-card-icon.amber {
            background: rgba(245, 158, 11, 0.12);
            color: #d97706;
        }

        .am-card-icon.indigo {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .am-card-icon.slate {
            background: rgba(100, 116, 139, 0.1);
            color: #475569;
        }

        .am-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .am-card-subtitle {
            font-size: 11px;
            color: #94a3b8;
            margin: 0;
        }

        .am-card-body {
            padding: 20px 24px;
        }

        /* ===== ALERT BANNERS ===== */
        .am-alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        .am-alert.info {
            background: var(--accent-soft);
            border: 1px solid var(--accent-soft);
            color: #4c3d9e;
        }

        .am-alert.warning {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #92400e;
        }

        .am-alert.success {
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #065f46;
        }

        .am-alert.danger {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #991b1b;
        }

        .am-alert-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
        }

        .am-alert.info .am-alert-icon {
            background: var(--accent-soft);
            color: var(--accent);
        }

        .am-alert.warning .am-alert-icon {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
        }

        .am-alert.success .am-alert-icon {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
        }

        .am-alert.danger .am-alert-icon {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
        }

        /* ===== FORM FIELDS ===== */
        .am-field {
            display: flex;
            flex-direction: column;
        }

        .am-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .am-input,
        .am-select {
            width: 100%;
            padding: 9px 12px;
            font-size: 13px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            background: #fafafa;
            color: #1e293b;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            outline: none;
        }

        .am-input:hover,
        .am-select:hover {
            border-color: #c4b5fd;
            background: #fff;
        }

        .am-input:focus,
        .am-select:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .am-select-wrap {
            position: relative;
        }

        /* ===== BUTTONS ===== */
        .am-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.18s;
            white-space: nowrap;
        }

        .am-btn-primary {
            background: linear-gradient(135deg, var(--accent-grad-1), var(--accent-grad-2));
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-shadow);
        }

        .am-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px var(--accent-shadow);
        }

        .am-btn-amber {
            background: var(--accent) !important;
            color: #fff;
            box-shadow: 0 4px 12px var(--accent-shadow);
        }

        .am-btn-amber:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px var(--accent-shadow);
        }

        .am-btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .am-btn-save {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .am-btn-save:hover {
            background: #059669;
            color: #fff;
            transform: scale(1.08);
        }

        .am-btn-del {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .am-btn-del:hover {
            background: #dc2626;
            color: #fff;
            transform: scale(1.08);
        }

        .am-btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .am-btn-edit:hover {
            background: #d97706;
            color: #fff;
            transform: scale(1.08);
        }

        /* ===== EDIT MODAL ===== */
        .am-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .am-modal {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.18);
            width: 100%;
            max-width: 640px;
            overflow: hidden;
            animation: amModalIn 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes amModalIn {
            from {
                opacity: 0;
                transform: scale(0.92) translateY(12px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .am-modal-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        }

        .am-modal-header-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
        }

        .am-modal-header h2 {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 2px;
        }

        .am-modal-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 12px;
            margin: 0;
        }

        .am-modal-body {
            padding: 24px;
        }

        .am-modal-section {
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .am-modal-section.requester {
            background: #f8f9ff;
            border: 1px solid var(--accent-soft);
        }

        .am-modal-section.approver {
            background: #f8fff9;
            border: 1px solid rgba(16, 185, 129, 0.12);
        }

        .am-modal-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 12px;
        }

        .am-modal-section.requester .am-modal-section-title {
            color: var(--accent);
        }

        .am-modal-section.approver .am-modal-section-title {
            color: #059669;
        }

        .am-modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 16px 24px;
            border-top: 1px solid #f1f5f9;
            background: #fafafa;
        }

        .am-btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }

        .am-btn-cancel:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        /* ===== TABLE ===== */
        .am-table {
            width: 100%;
            border-collapse: collapse;
        }

        .am-table thead tr {
            background: linear-gradient(to right, var(--accent-soft), var(--accent-soft));
        }

        .am-table thead th {
            padding: 11px 14px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--accent);
            border-bottom: 2px solid var(--accent-soft);
            white-space: nowrap;
        }

        .am-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.12s;
        }

        .am-table tbody tr:last-child {
            border-bottom: none;
        }

        .am-table tbody tr:hover {
            background: var(--accent-soft);
        }

        .am-table td {
            padding: 10px 14px;
            vertical-align: middle;
        }

        .am-table-input {
            width: 100%;
            padding: 7px 10px;
            font-size: 12px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            color: #1e293b;
            outline: none;
            cursor: default;
        }

        .am-table-input:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 3px var(--accent-soft);
        }

        .am-table-select {
            width: 100%;
            padding: 4px 6px;
            font-size: 10.5px;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            background: #f8fafc;
            color: #475569;
            margin-top: 4px;
            outline: none;
            transition: all 0.15s;
        }

        .am-table-select:focus {
            border-color: var(--accent);
            background: #fff;
            box-shadow: 0 0 0 2px var(--accent-soft);
        }

        .am-badge-stage2 {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            background: rgba(100, 116, 139, 0.08);
            color: #475569;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* ===== EMPTY STATE ===== */
        .am-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
            text-align: center;
        }

        .am-empty-icon {
            width: 64px;
            height: 64px;
            background: var(--accent-soft);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #c4b5fd;
            margin-bottom: 16px;
        }

        /* ===== FORM SECTIONS (Requester/Approver) ===== */
        .am-section {
            border-radius: 14px;
            padding: 14px;
        }

        .am-section.requester {
            background: #f8f9ff;
            border: 1px solid var(--accent-soft);
        }

        .am-section.approver {
            background: #f8fff9;
            border: 1px solid rgba(16, 185, 129, 0.12);
        }

        .am-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 12px;
        }

        /* ===== DARK MODE ===== */
        html.dark .am-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
        }

        html.dark .am-card-header {
            background: linear-gradient(to right, rgba(118, 100, 228, 0.08), transparent);
            border-bottom-color: #334155;
        }

        html.dark .am-card-title {
            color: #ffffff !important;
        }

        html.dark .am-card-subtitle {
            color: #94a3b8;
        }

        html.dark .am-card-body {
            background: transparent;
        }

        html.dark .am-label {
            color: #ffffff !important;
        }

        html.dark .am-input,
        html.dark .am-select {
            background: #111c2e;
            color: #ffffff !important;
            border-color: #475569;
        }

        html.dark .am-input option,
        html.dark .am-select option {
            background: #111c2e;
            color: #ffffff;
        }

        html.dark .am-input:hover,
        html.dark .am-select:hover {
            background: #1a2538;
            border-color: #64748b;
        }

        html.dark .am-input:focus,
        html.dark .am-select:focus {
            background: #111c2e;
            border-color: var(--accent);
            color: #ffffff !important;
        }

        html.dark .am-input::placeholder {
            color: #64748b;
        }

        html.dark .am-alert.info {
            background: rgba(118, 100, 228, 0.12);
            color: #c4b5fd;
        }

        html.dark .am-alert.warning {
            background: rgba(245, 158, 11, 0.12);
            color: #fbbf24;
        }

        html.dark .am-alert.success {
            background: rgba(16, 185, 129, 0.12);
            color: #6ee7b7;
        }

        html.dark .am-alert.danger {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
        }

        html.dark .am-empty {
            color: #64748b;
        }

        html.dark .am-empty p {
            color: #94a3b8;
        }

        html.dark .am-section.requester {
            background: rgba(118, 100, 228, 0.08);
            border-color: rgba(118, 100, 228, 0.2);
        }

        html.dark .am-section.approver {
            background: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.2);
        }

        html.dark .am-section-title {
            color: inherit;
        }

        html.dark .am-table thead th {
            color: #ffffff !important;
            border-bottom-color: rgba(255, 255, 255, 0.12);
        }

        html.dark .am-table tbody tr {
            border-bottom-color: #334155;
        }

        html.dark .am-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        html.dark .am-table td {
            color: #ffffff;
        }

        html.dark .am-table-input {
            background: #111c2e;
            color: #ffffff !important;
            border-color: #475569;
        }

        html.dark .am-table-input:focus {
            border-color: var(--accent);
            background: #111c2e;
            color: #ffffff !important;
        }

        html.dark .am-table-select {
            background: #111c2e;
            color: #ffffff !important;
            border-color: #475569;
        }

        html.dark .am-table-select option {
            background: #111c2e;
            color: #ffffff;
        }

        html.dark .am-table-select:focus {
            border-color: var(--accent);
            background: #111c2e;
            color: #ffffff !important;
        }

        html.dark .am-badge-stage2 {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
        }

        html.dark .am-modal {
            background: #1e293b;
            border: 1px solid #334155;
        }

        html.dark .am-modal-header {
            border-bottom-color: #334155;
        }

        html.dark .am-modal-header h2 {
            color: #ffffff;
        }

        html.dark .am-modal-footer {
            border-top-color: #334155;
            background: #1e293b;
        }
    </style>
@endpush

@section('content')
    {{-- ===== EDIT MODAL (Alpine.js) ===== --}}
    <div x-data="editMappingModal()" @keydown.escape.window="close()">
        <template x-if="open">
            <div class="am-modal-overlay" @click.self="close()">
                <div class="am-modal" @click.stop>

                    {{-- Modal Header --}}
                    <div class="am-modal-header">
                        <div class="am-modal-header-icon"><i class="fas fa-edit"></i></div>
                        <div>
                            <h2>Edit Mapping Approver</h2>
                            <p>Ubah nama, jabatan requester dan approver</p>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <form :action="url" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_method" value="PUT">

                        <div class="am-modal-body">

                            {{-- Requester Section --}}
                            <div class="am-modal-section requester">
                                <p class="am-modal-section-title">
                                    <i class="fas fa-user mr-1"></i> Requester
                                </p>
                                <div class="am-field">
                                    <label class="am-label">Jabatan Requester <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="requester_jabatan" required class="am-input"
                                        placeholder="Jabatan requester" x-model="reqJabatan">
                                </div>
                            </div>

                            {{-- Approver Section --}}
                            <div class="am-modal-section approver">
                                <p class="am-modal-section-title">
                                    <i class="fas fa-user-check mr-1"></i> Approver 1
                                </p>
                                <div class="am-field">
                                    <label class="am-label">Jabatan Approver 1 <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="approver_jabatan" required class="am-input"
                                        placeholder="Jabatan approver" x-model="apprJabatan">
                                </div>
                            </div>

                        </div>

                        {{-- Modal Footer --}}
                        <div class="am-modal-footer">
                            <button type="button" class="am-btn am-btn-cancel" @click="close()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                            <button type="submit" class="am-btn am-btn-amber">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </template>
    </div>
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- ===== PAGE HEADER ===== --}}
                <div class="am-page-header">
                    <div style="display:flex; align-items:center; gap:16px;">
                        <div class="am-header-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <h1>Approval Change Request</h1>
                            <p>Konfigurasi Alur Approval Change Request</p>
                        </div>
                    </div>
                </div>

                {{-- Flash Messages --}}
                @if (session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: {!! json_encode(session('success')) !!},
                                confirmButtonColor: 'var(--accent)',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn-swal-success'
                                }
                            });
                        });
                    </script>
                @endif
                @if (session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: {!! json_encode(session('error')) !!},
                                confirmButtonColor: 'var(--accent)',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn-swal-success'
                                }
                            });
                        });
                    </script>
                @endif

                {{-- ===== TAHAP 2: MANAJER UMUM ===== --}}
                <div class="am-card">
                    <div class="am-card-header">
                        <div class="am-card-icon amber">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <p class="am-card-title">Approver 2</p>
                            <p class="am-card-subtitle">Jabatan: {{ $stage2 }}</p>
                        </div>
                    </div>
                    <div class="am-card-body">
                        <form action="{{ route('approval-mapping.stage2-user') }}" method="POST">
                            @csrf
                            <div style="display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;">
                                <div class="am-field" style="flex:1 1 320px; min-width:260px;">
                                    <label class="am-label">User ({{ $stage2 }})</label>
                                    <div class="am-select-wrap">
                                        <select name="stage2_user_id" class="am-select">
                                            <option value="">— Pilih User: {{ $stage2 }} —</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}" @selected($stage2UserId == $u->id)>
                                                    {{ $u->display_name }} — {{ $u->jabatan }} ({{ $u->unit }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="am-btn am-btn-amber">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ===== FORM TAMBAH MAPPING ===== --}}
                <div class="am-card">
                    <div class="am-card-header">
                        <div class="am-card-icon indigo">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <p class="am-card-title">Tambah Mapping Baru</p>
                        </div>
                    </div>
                    <div class="am-card-body">
                        <form action="{{ route('approval-mapping.store') }}" method="POST" id="form-tambah-mapping">
                            @csrf
                            {{-- Baris 1: Requester & Approver sejajar --}}
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:14px;">

                                {{-- Kolom Requester --}}
                                <div class="am-section requester">
                                    <p class="am-section-title" style="color:var(--accent);">
                                        <i class="fas fa-user mr-1"></i> Requester
                                    </p>
                                    <div class="am-field" style="margin-bottom:10px;">
                                        <label class="am-label">User Requester</label>
                                        <div class="am-select-wrap">
                                            <select name="requester_user_id" class="js-user-peminta am-select">
                                                <option value="">— Pilih User —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}" data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->display_name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Requester <span
                                                style="color:#ef4444">*</span></label>
                                        <input type="text" name="requester_jabatan" id="req-jabatan" required readonly
                                            list="jabatanList" class="am-input" placeholder="Contoh: SPV Akuntansi">
                                    </div>
                                </div>

                                {{-- Kolom Approver 1 --}}
                                <div class="am-section approver">
                                    <p class="am-section-title" style="color:#059669;">
                                        <i class="fas fa-user-check mr-1"></i> Approver 1
                                    </p>
                                    <div class="am-field" style="margin-bottom:10px;">
                                        <label class="am-label">User Approver 1</label>
                                        <div class="am-select-wrap">
                                            <select name="approver_user_id" class="js-user-atasan am-select">
                                                <option value="">— Pilih User —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}"
                                                        data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->display_name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Approver 1 <span
                                                style="color:#ef4444">*</span></label>
                                        <input type="text" name="approver_jabatan" id="appr-jabatan" required readonly
                                            list="jabatanList" class="am-input" placeholder="Contoh: Manajer Keuangan">
                                    </div>
                                </div>
                            </div>

                            {{-- Baris 2: Tombol Submit full width --}}
                            <button type="submit" class="am-btn am-btn-primary"
                                style="width:100%; justify-content:center; padding:11px 18px;">
                                <i class="fas fa-plus"></i> Tambah Mapping
                            </button>
                        </form>

                        <datalist id="jabatanList">
                            @foreach ($jabatanList as $j)
                                <option value="{{ $j }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                {{-- ===== DAFTAR MAPPING ===== --}}
                <div class="am-card" id="mapping-list">
                    <div class="am-card-header">
                        <div class="am-card-icon slate">
                            <i class="fas fa-list-ul"></i>
                        </div>
                        <div style="flex:1;">
                            <p class="am-card-title">Daftar Mapping Approver 1</p>
                            <p class="am-card-subtitle">Edit langsung di tabel, lalu klik tombol simpan</p>
                        </div>
                        <span
                            style="background:var(--accent-soft); color:var(--accent); font-size:11px; font-weight:700; padding:4px 12px; border-radius:999px;">
                            {{ $mappings->count() }} mapping
                        </span>
                    </div>
                    <div style="padding:0;">
                        @if ($mappings->isEmpty())
                            <div class="am-empty">
                                <div class="am-empty-icon">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <p style="font-size:14px; font-weight:600; margin:0 0 6px;">Belum ada
                                    mapping</p>
                                <p style="font-size:12px; color:#9ca3af; margin:0;">Gunakan form di atas untuk menambahkan
                                    mapping approver.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="am-table">
                                    <thead>
                                        <tr>
                                            <th style="width:38px; text-align:center;">No</th>
                                            <th>Requester</th>
                                            <th>Approver 1</th>
                                            <th style="text-align:center;">Approver 2</th>
                                            <th style="text-align:center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mappings as $i => $mapping)
                                            <tr>
                                                <form action="{{ route('approval-mapping.update', $mapping->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')

                                                    <td style="text-align:center;">
                                                        <span
                                                            style="font-size:11px; color:#94a3b8; font-weight:600;">{{ $i + 1 }}</span>
                                                    </td>

                                                    {{-- Peminta --}}
                                                    <td style="min-width:140px;">
                                                        <input type="text" name="requester_jabatan"
                                                            value="{{ $mapping->requester_jabatan }}" required readonly
                                                            class="am-table-input readonly-field" list="jabatanList"
                                                            placeholder="Jabatan peminta">
                                                        <select name="requester_user_id"
                                                            class="js-user-peminta am-table-select">
                                                            <option value="">— Pilih User —</option>
                                                            @foreach ($users as $u)
                                                                <option value="{{ $u->id }}"
                                                                    data-jabatan="{{ $u->jabatan }}"
                                                                    @selected($mapping->requester_user_id == $u->id)>
                                                                    {{ $u->display_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    {{-- Atasan --}}
                                                    <td style="min-width:140px;">
                                                        <input type="text" name="approver_jabatan"
                                                            value="{{ $mapping->approver_jabatan }}" required readonly
                                                            class="am-table-input readonly-field" list="jabatanList"
                                                            placeholder="Jabatan atasan">
                                                        <select name="approver_user_id"
                                                            class="js-user-atasan am-table-select">
                                                            <option value="">— Pilih User —</option>
                                                            @foreach ($users as $u)
                                                                <option value="{{ $u->id }}"
                                                                    data-jabatan="{{ $u->jabatan }}"
                                                                    @selected($mapping->approver_user_id == $u->id)>
                                                                    {{ $u->display_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    {{-- Tahap 2 --}}
                                                    <td style="text-align:center;">
                                                        <span class="am-badge-stage2">
                                                            {{ $stage2 }}
                                                        </span>
                                                    </td>

                                                    {{-- Aksi --}}
                                                    <td>
                                                        <div
                                                            style="display:flex; align-items:center; justify-content:center; gap:6px;">
                                                            <button type="submit" title="Simpan perubahan"
                                                                class="am-btn-icon am-btn-save">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            {{-- <button type="button" title="Edit mapping"
                                                                class="am-btn-icon am-btn-edit"
                                                                onclick="window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: {
                                                                    url: '{{ route('approval-mapping.update', $mapping->id) }}',
                                                                    reqJabatan: '{{ addslashes($mapping->requester_jabatan) }}',
                                                                    apprJabatan: '{{ addslashes($mapping->approver_jabatan) }}'
                                                                } }))">
                                                                <i class="fas fa-edit"></i>
                                                            </button> --}}
                                                </form>
                                                <form action="{{ route('approval-mapping.destroy', $mapping->id) }}"
                                                    method="POST" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Hapus"
                                                        class="am-btn-icon am-btn-del delete-button"
                                                        data-confirm="Yakin ingin menghapus mapping approver ini?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                            </div>
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>

        <script>
            // ── Inisialisasi Select2 ──────────────────────────────────────────
            function initSelect2(context) {
                var $ctx = context ? $(context) : $(document);

                // Stage2 user
                $ctx.find('select[name="stage2_user_id"]').each(function() {
                    $(this).select2({
                        placeholder: '— Pilih User —',
                        width: '100%'
                    });
                });

                // Requester user (form tambah + tabel)
                $ctx.find('.js-user-peminta').each(function() {
                    var $sel = $(this);
                    $sel.select2({
                        placeholder: '— Pilih User —',
                        width: '100%'
                    });
                    // Auto-fill jabatan saat pilih user
                    $sel.on('select2:select select2:clear', function() {
                        var opt = this.options[this.selectedIndex];
                        var jabatan = opt ? opt.dataset.jabatan : '';
                        var target = $sel.closest('tr').length ?
                            $sel.closest('tr').find('input[name="requester_jabatan"]')[0] :
                            document.getElementById('req-jabatan');
                        if (target) target.value = jabatan || '';
                    });
                });

                // Approver user (form tambah + tabel)
                $ctx.find('.js-user-atasan').each(function() {
                    var $sel = $(this);
                    $sel.select2({
                        placeholder: '— Pilih User —',
                        width: '100%'
                    });
                    $sel.on('select2:select select2:clear', function() {
                        var opt = this.options[this.selectedIndex];
                        var jabatan = opt ? opt.dataset.jabatan : '';
                        var target = $sel.closest('tr').length ?
                            $sel.closest('tr').find('input[name="approver_jabatan"]')[0] :
                            document.getElementById('appr-jabatan');
                        if (target) target.value = jabatan || '';
                    });
                });
            }

            // ── Inisialisasi Select2 Modal Edit ──────────────────────────────
            function initModalSelect2() {
                // Destroy dulu jika sudah ada instance sebelumnya
                $('#modal-req-user, #modal-appr-user').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });

                $('#modal-req-user').select2({
                    placeholder: '— Pilih User —',
                    width: '100%',
                    dropdownParent: $('#modal-req-user').parent()
                }).on('select2:select select2:clear', function() {
                    var opt = this.options[this.selectedIndex];
                    var jabatan = opt ? opt.dataset.jabatan : '';
                    // update Alpine data via native change event
                    var ev = new Event('change', {
                        bubbles: true
                    });
                    this.dispatchEvent(ev);
                });

                $('#modal-appr-user').select2({
                    placeholder: '— Pilih User —',
                    width: '100%',
                    dropdownParent: $('#modal-appr-user').parent()
                }).on('select2:select select2:clear', function() {
                    var ev = new Event('change', {
                        bubbles: true
                    });
                    this.dispatchEvent(ev);
                });
            }

            // Run on page load
            $(document).ready(function() {
                initSelect2(null);

                // Validasi sebelum submit form tambah mapping
                $('#form-tambah-mapping').on('submit', function(e) {
                    var reqUser = $(this).find('select[name="requester_user_id"]').val();
                    var apprUser = $(this).find('select[name="approver_user_id"]').val();

                    if (!reqUser || !apprUser) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'User Belum Dipilih',
                            text: 'User Requester dan User Approver harus dipilih terlebih dahulu!',
                            confirmButtonColor: 'var(--accent)',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn-swal-success'
                            }
                        });
                    }
                });

                // Re-init Select2 saat modal edit dibuka
                window.addEventListener('open-edit-modal', function(e) {
                    // Tunggu Alpine render modal dulu (x-if)
                    setTimeout(function() {
                        initModalSelect2();
                        // Set nilai yang sudah ada
                        if (e.detail.reqUserId) {
                            $('#modal-req-user').val(e.detail.reqUserId).trigger('change.select2');
                        }
                        if (e.detail.apprUserId) {
                            $('#modal-appr-user').val(e.detail.apprUserId).trigger('change.select2');
                        }
                    }, 80);
                });
            });

            // Alpine.js component for Edit Modal
            function editMappingModal() {
                return {
                    open: false,
                    url: '',
                    reqJabatan: '',
                    apprJabatan: '',
                    init() {
                        window.addEventListener('open-edit-modal', (e) => {
                            this.url = e.detail.url;
                            this.reqJabatan = e.detail.reqJabatan;
                            this.apprJabatan = e.detail.apprJabatan;
                            this.open = true;
                        });
                    },
                    close() {
                        // Destroy Select2 sebelum modal ditutup
                        if (window.$) {
                            $('#modal-req-user, #modal-appr-user').each(function() {
                                if ($(this).hasClass('select2-hidden-accessible')) {
                                    $(this).select2('destroy');
                                }
                            });
                        }
                        this.open = false;
                    }
                };
            }
        </script>
    @endpush
