@extends('layouts.app')

@section('title', 'SIGERCEP - Atasan Langsung')

@push('styles')
    <style>
        /* ===== PAGE HEADER ===== */
        .am-page-header {
            background: linear-gradient(135deg, #7664E4 0%, #9b8df0 60%, #b8aef6 100%);
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
            border: 1px solid rgba(118, 100, 228, 0.1);
            box-shadow: 0 4px 24px rgba(118, 100, 228, 0.06), 0 1px 4px rgba(0, 0, 0, 0.04);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .am-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 24px;
            border-bottom: 1px solid #f3f4f6;
            background: linear-gradient(to right, rgba(118, 100, 228, 0.03), transparent);
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
            background: rgba(118, 100, 228, 0.12);
            color: #7664E4;
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
            background: rgba(118, 100, 228, 0.07);
            border: 1px solid rgba(118, 100, 228, 0.15);
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
            background: rgba(118, 100, 228, 0.15);
            color: #7664E4;
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
            appearance: none;
            -webkit-appearance: none;
        }

        .am-input:hover,
        .am-select:hover {
            border-color: #c4b5fd;
            background: #fff;
        }

        .am-input:focus,
        .am-select:focus {
            border-color: #7664E4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(118, 100, 228, 0.12);
        }

        .am-select-wrap {
            position: relative;
        }

        .am-select-wrap::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            color: #94a3b8;
            pointer-events: none;
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
            background: linear-gradient(135deg, #7664E4, #9b8df0);
            color: #fff;
            box-shadow: 0 4px 12px rgba(118, 100, 228, 0.35);
        }

        .am-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(118, 100, 228, 0.45);
        }

        .am-btn-amber {
            background: linear-gradient(135deg, #f59e0b, #fbbf24);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
        }

        .am-btn-amber:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
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
            from { opacity: 0; transform: scale(0.92) translateY(12px); }
            to   { opacity: 1; transform: scale(1)   translateY(0); }
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
            color: rgba(255,255,255,0.8);
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
            border: 1px solid rgba(118, 100, 228, 0.12);
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

        .am-modal-section.requester .am-modal-section-title { color: #7664E4; }
        .am-modal-section.approver  .am-modal-section-title { color: #059669; }

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
            background: linear-gradient(to right, rgba(118, 100, 228, 0.05), rgba(118, 100, 228, 0.02));
        }

        .am-table thead th {
            padding: 11px 14px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #7664E4;
            border-bottom: 2px solid rgba(118, 100, 228, 0.12);
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
            background: rgba(118, 100, 228, 0.03);
        }

        .am-table td {
            padding: 10px 14px;
            vertical-align: middle;
        }

        .am-table-input {
            width: 100%;
            padding: 7px 10px;
            font-size: 12px;
            border: 1.5px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: #1e293b;
            transition: all 0.15s;
            outline: none;
        }

        .am-table-input:hover {
            border-color: #e2e8f0;
            background: #f8fafc;
        }

        .am-table-input:focus {
            border-color: #7664E4;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(118, 100, 228, 0.1);
        }

        .am-table-select {
            width: 100%;
            padding: 5px 10px;
            font-size: 11px;
            border: 1.5px solid #e2e8f0;
            border-radius: 7px;
            background: #f8fafc;
            color: #475569;
            margin-top: 4px;
            outline: none;
            transition: all 0.15s;
        }

        .am-table-select:focus {
            border-color: #7664E4;
            background: #fff;
            box-shadow: 0 0 0 2px rgba(118, 100, 228, 0.1);
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
            background: rgba(118, 100, 228, 0.08);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #c4b5fd;
            margin-bottom: 16px;
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
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div class="am-field">
                                        <label class="am-label">User Requester</label>
                                        <div class="am-select-wrap">
                                            <select name="requester_user_id" class="am-select" x-model="reqUserId"
                                                @change="reqJabatan = $event.target.selectedOptions[0].dataset.jabatan || reqJabatan">
                                                <option value="">— Pilih user —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}" data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Requester <span style="color:#ef4444">*</span></label>
                                        <input type="text" name="requester_jabatan" required
                                            list="jabatanList" class="am-input"
                                            placeholder="Jabatan requester"
                                            x-model="reqJabatan">
                                    </div>
                                </div>
                            </div>

                            {{-- Approver Section --}}
                            <div class="am-modal-section approver">
                                <p class="am-modal-section-title">
                                    <i class="fas fa-user-check mr-1"></i> Approver 1
                                </p>
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div class="am-field">
                                        <label class="am-label">User Approver 1</label>
                                        <div class="am-select-wrap">
                                            <select name="approver_user_id" class="am-select" x-model="apprUserId"
                                                @change="apprJabatan = $event.target.selectedOptions[0].dataset.jabatan || apprJabatan">
                                                <option value="">— Pilih user —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}" data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Approver 1 <span style="color:#ef4444">*</span></label>
                                        <input type="text" name="approver_jabatan" required
                                            list="jabatanList" class="am-input"
                                            placeholder="Jabatan approver"
                                            x-model="apprJabatan">
                                    </div>
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
                    <div class="am-alert success">
                        <div class="am-alert-icon"><i class="fas fa-check"></i></div>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif
                @if (session('error'))
                    <div class="am-alert danger">
                        <div class="am-alert-icon"><i class="fas fa-times"></i></div>
                        <div>{{ session('error') }}</div>
                    </div>
                @endif

                {{-- ===== TAHAP 2: MANAJER UMUM ===== --}}
                <div class="am-card">
                    <div class="am-card-header">
                        <div class="am-card-icon amber">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <p class="am-card-title">Approver Tahap 2</p>
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
                                            <option value="">— Gunakan jabatan: {{ $stage2 }} —</option>
                                            @foreach ($users as $u)
                                                <option value="{{ $u->id }}" @selected($stage2UserId == $u->id)>
                                                    {{ $u->name }} — {{ $u->jabatan }} ({{ $u->unit }})
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
                        <form action="{{ route('approval-mapping.store') }}" method="POST">
                            @csrf
                            <div
                                style="display:grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap:14px; align-items:end;">

                                {{-- Kolom Requester --}}
                                <div
                                    style="background:#f8f9ff; border-radius:14px; padding:14px; border:1px solid rgba(118,100,228,0.1);">
                                    <p
                                        style="font-size:11px; font-weight:700; color:#7664E4; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 12px;">
                                        <i class="fas fa-user mr-1"></i> Requester
                                    </p>
                                    <div class="am-field" style="margin-bottom:10px;">
                                        <label class="am-label">User Requester</label>
                                        <div class="am-select-wrap">
                                            <select name="requester_user_id" class="js-user-peminta am-select">
                                                <option value="">— Pilih user —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}"
                                                        data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Requester <span
                                                style="color:#ef4444">*</span></label>
                                        <input type="text" name="requester_jabatan" id="req-jabatan" required disabled
                                            list="jabatanList" class="am-input" placeholder="Contoh: SPV Akuntansi">
                                    </div>
                                </div>

                                {{-- Kolom Approver 1 --}}
                                <div
                                    style="background:#f8fff9; border-radius:14px; padding:14px; border:1px solid rgba(16,185,129,0.12);">
                                    <p
                                        style="font-size:11px; font-weight:700; color:#059669; text-transform:uppercase; letter-spacing:0.05em; margin:0 0 12px;">
                                        <i class="fas fa-user-check mr-1"></i> Approver 1
                                    </p>
                                    <div class="am-field" style="margin-bottom:10px;">
                                        <label class="am-label">User Approver 1</label>
                                        <div class="am-select-wrap">
                                            <select name="approver_user_id" class="js-user-atasan am-select">
                                                <option value="">— Pilih user —</option>
                                                @foreach ($users as $u)
                                                    <option value="{{ $u->id }}"
                                                        data-jabatan="{{ $u->jabatan }}">
                                                        {{ $u->name }} — {{ $u->jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="am-field">
                                        <label class="am-label">Jabatan Approver 1 <span
                                                style="color:#ef4444">*</span></label>
                                        <input type="text" name="approver_jabatan" id="appr-jabatan" required disabled
                                            list="jabatanList" class="am-input" placeholder="Contoh: Manajer Keuangan">
                                    </div>
                                </div>

                                {{-- Tombol Submit --}}
                                <div style="display:flex; align-items:flex-end;">
                                    <button type="submit" class="am-btn am-btn-primary"
                                        style="width:100%; justify-content:center;">
                                        <i class="fas fa-plus"></i> Tambah Mapping
                                    </button>
                                </div>
                            </div>
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
                            style="background:rgba(118,100,228,0.1); color:#7664E4; font-size:11px; font-weight:700; padding:4px 12px; border-radius:999px;">
                            {{ $mappings->count() }} mapping
                        </span>
                    </div>
                    <div style="padding:0;">
                        @if ($mappings->isEmpty())
                            <div class="am-empty">
                                <div class="am-empty-icon">
                                    <i class="fas fa-user-slash"></i>
                                </div>
                                <p style="font-size:14px; font-weight:600; color:#374151; margin:0 0 6px;">Belum ada
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
                                                    <td style="min-width:220px;">
                                                        <input type="text" name="requester_jabatan"
                                                            value="{{ $mapping->requester_jabatan }}" required readonly
                                                            style="background-color: #f1f5f9; cursor: not-allowed;"
                                                            list="jabatanList" class="am-table-input"
                                                            placeholder="Jabatan peminta">
                                                        <select name="requester_user_id"
                                                            class="js-user-peminta am-table-select">
                                                            <option value="">— Pilih user —</option>
                                                            @foreach ($users as $u)
                                                                <option value="{{ $u->id }}"
                                                                    data-jabatan="{{ $u->jabatan }}"
                                                                    @selected($mapping->requester_user_id == $u->id)>
                                                                    {{ $u->name }} — {{ $u->jabatan }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
 
                                                    {{-- Atasan --}}
                                                    <td style="min-width:220px;">
                                                        <input type="text" name="approver_jabatan"
                                                            value="{{ $mapping->approver_jabatan }}" required readonly
                                                            style="background-color: #f1f5f9; cursor: not-allowed;"
                                                            list="jabatanList" class="am-table-input"
                                                            placeholder="Jabatan atasan">
                                                        <select name="approver_user_id"
                                                            class="js-user-atasan am-table-select">
                                                            <option value="">— Pilih user —</option>
                                                            @foreach ($users as $u)
                                                                <option value="{{ $u->id }}"
                                                                    data-jabatan="{{ $u->jabatan }}"
                                                                    @selected($mapping->approver_user_id == $u->id)>
                                                                    {{ $u->name }} — {{ $u->jabatan }}
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
                                                            <button type="button" title="Edit mapping"
                                                                class="am-btn-icon am-btn-edit"
                                                                onclick="window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: {
                                                                    url: '{{ route('approval-mapping.update', $mapping->id) }}',
                                                                    reqUserId: '{{ $mapping->requester_user_id ?? '' }}',
                                                                    reqJabatan: '{{ addslashes($mapping->requester_jabatan) }}',
                                                                    apprUserId: '{{ $mapping->approver_user_id ?? '' }}',
                                                                    apprJabatan: '{{ addslashes($mapping->approver_jabatan) }}'
                                                                } }))">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            </form>
                                                            <form action="{{ route('approval-mapping.destroy', $mapping->id) }}"
                                                                method="POST"
                                                                style="display:inline;"
                                                                class="delete-form">
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
    </div>
    </div>

    <script>
        // Pilih user -> isi jabatan otomatis (form tambah)
        document.querySelectorAll('.js-user-peminta').forEach(function(sel) {
            sel.addEventListener('change', function() {
                var opt = sel.selectedOptions[0];
                var target = sel.closest('tr') ?
                    sel.closest('tr').querySelector('input[name="requester_jabatan"]') :
                    document.getElementById('req-jabatan');
                if (target && opt && opt.dataset.jabatan) {
                    target.value = opt.dataset.jabatan;
                }
            });
        });
        document.querySelectorAll('.js-user-atasan').forEach(function(sel) {
            sel.addEventListener('change', function() {
                var opt = sel.selectedOptions[0];
                var target = sel.closest('tr') ?
                    sel.closest('tr').querySelector('input[name="approver_jabatan"]') :
                    document.getElementById('appr-jabatan');
                if (target && opt && opt.dataset.jabatan) {
                    target.value = opt.dataset.jabatan;
                }
            });
        });

        // Alpine.js component for Edit Modal
        function editMappingModal() {
            return {
                open: false,
                url: '',
                reqUserId: '',
                reqJabatan: '',
                apprUserId: '',
                apprJabatan: '',
                init() {
                    window.addEventListener('open-edit-modal', (e) => {
                        this.url        = e.detail.url;
                        this.reqUserId  = e.detail.reqUserId;
                        this.reqJabatan = e.detail.reqJabatan;
                        this.apprUserId = e.detail.apprUserId;
                        this.apprJabatan = e.detail.apprJabatan;
                        this.open = true;
                    });
                },
                close() {
                    this.open = false;
                }
            };
        }
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/alert-delete-swal.js') }}"></script>
@endpush
