@extends('layouts.app')

@section('title', 'SIGERCEP')

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/permissions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/search.css') }}">

    <style>
        .btn-rule-edit {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 28px !important;
            height: 28px !important;
            border-radius: 6px !important;
            border: 1px solid rgba(118, 100, 228, 0.22) !important;
            background: transparent !important;
            color: #7664E4 !important;
            font-size: 0.75rem !important;
            cursor: pointer !important;
            flex-shrink: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            transition: all 0.2s !important;
        }

        .btn-rule-edit:hover {
            background: #f0ecff !important;
            border-color: #7664E4 !important;
            color: #6453d4 !important;
        }

        .rule-tab-btn.active {
            color: #7664E4 !important;
            border-bottom-color: #7664E4 !important;
            background: #fff !important;
        }
        .rule-tab-btn.active .rule-tab-badge {
            background: #7664E4 !important;
            color: #fff !important;
        }
        .rule-list-icon {
            color: #7664E4 !important;
            background: #f0ecff !important;
            border-color: rgba(118, 100, 228, 0.18) !important;
        }
        .rule-list-item:hover {
            border-color: #7664E4 !important;
        }
    </style>
@endpush

@section('content')
    <div class="perm-page">

        {{-- Header --}}
        <div class="perm-header">
            <div class="perm-header-info">
                <h1><i class="fas fa-shield-halved"></i> Manajemen Hak Akses</h1>
                <p>Kelola permission dan aturan akses tiap modul sistem</p>
            </div>
            <button class="btn-add-perm" onclick="openPermModal()" type="button">
                <i class="fas fa-plus"></i> Tambah Permission
            </button>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="perm-alert perm-alert-success">
                <i class="fas fa-circle-check" style="font-size: 1rem; margin-top: 1px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="perm-alert perm-alert-error">
                <i class="fas fa-circle-exclamation" style="font-size: 1rem; margin-top: 1px;"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Stats --}}
        <div class="perm-stats">
            <div class="perm-stat">
                <div class="perm-stat-label"><i class="fas fa-key"></i> Total permission</div>
                <div class="perm-stat-value" id="totalPermissions">{{ $permissions->count() }}</div>
                <div class="perm-stat-sub">permission terdaftar</div>
            </div>
            <div class="perm-stat">
                <div class="perm-stat-label"><i class="fas fa-tags"></i> Rule aktif</div>
                <div class="perm-stat-value">{{ $permissions->sum(fn($p) => $p->rules->count()) }}</div>
                <div class="perm-stat-sub">total rule berlaku</div>
            </div>
            <div class="perm-stat">
                <div class="perm-stat-label"><i class="fas fa-layer-group"></i> Menu terdaftar</div>
                <div class="perm-stat-value">{{ $permissions->pluck('menu')->unique()->count() }}</div>
                <div class="perm-stat-sub">modul berbeda</div>
            </div>
        </div>

        {{-- Search Bar --}}
        <div class="perm-search-container" style="margin-bottom: 1.5rem; width: 100%;">
            <div class="perm-search-wrapper" style="position: relative; width: 100%;">
                <i class="fas fa-search"
                    style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 1rem;"></i>
                <input type="text" id="searchMenuInput" class="perm-search-input"
                    placeholder="Cari menu berdasarkan nama..."
                    style="width: 100%; padding: 12px 50px 12px 48px; border: 1px solid #e2e8f0; border-radius: 12px; background: white; font-size: 0.9rem; outline: none; transition: all 0.2s;">
                <button type="button" id="clearSearchBtn" class="perm-search-clear"
                    style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; display: none;">
                    <i class="fas fa-times-circle" style="font-size: 1rem;"></i>
                </button>
            </div>
            <div class="search-info-wrapper">
                <div id="searchInfo" style="font-size: 0.75rem; color: #6b7280; display: none;">
                    <span id="searchResultCount"></span>
                </div>
                <div id="filterBadge" class="filter-badge" style="display: none;">
                    <i class="fas fa-filter"></i>
                    <span id="filterCount">0</span> data difilter
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="perm-card">
            <table class="perm-table">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th><i class="fas fa-sitemap" style="margin-right:5px"></i>Menu</th>
                        <th>Action</th>
                        <th>Rules (Hak Akses)</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="permissionsTableBody">
                    @forelse($permissions as $p)
                        @php
                            $badgeClass = match ($p->action) {
                                'create' => 'badge-create',
                                'read' => 'badge-read',
                                'update' => 'badge-update',
                                'delete' => 'badge-delete',
                                default => 'badge-read',
                            };
                            $ruleCount = $p->rules->count();
                        @endphp
                        <tr data-menu="{{ strtolower($p->menu) }}" data-permission-id="{{ $p->id }}">

                            {{-- Menu --}}
                            <td data-label="Menu">
                                <span class="menu-chip menu-text" title="{{ $p->menu }}">{{ $p->menu }}</span>
                            </td>

                            {{-- Action --}}
                            <td data-label="Action">
                                <span class="action-badge {{ $badgeClass }}">
                                    <span class="dot"></span>
                                    {{ strtoupper($p->action) }}
                                </span>
                            </td>

                            {{-- Rules ringkas --}}
                            <td data-label="Rules">
                                @if ($ruleCount > 0)
                                    <span class="rule-count-badge">
                                        <i class="fas fa-users"></i>
                                        {{ $ruleCount }} rule aktif
                                    </span>
                                @else
                                    <span class="rule-count-public">
                                        <i class="fas fa-globe"></i> Semua user (public)
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td data-label="Aksi">
                                <div class="perm-action-btns">
                                    <button type="button" class="btn-manage-rules"
                                        onclick="openRulePanel(
                {{ $p->id }},
                '{{ addslashes($p->menu) }}',
                '{{ $p->action }}'
            )">
                                        <i class="fas fa-sliders"></i> Kelola
                                    </button>

                                    <form action="{{ route('permissions.destroy', $p->id) }}" method="POST"
                                        class="form-delete" data-name="{{ $p->menu }} - {{ $p->action }}"
                                        style="display:inline;margin:0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete-perm btn-delete-trigger">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="4">
                                <div class="perm-empty">
                                    <div class="perm-empty-icon"><i class="fas fa-lock-open"></i></div>
                                    <h3>Belum ada permission</h3>
                                    <p>Klik "Tambah Permission" di atas untuk memulai</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Overlay --}}
    <div class="rule-panel-overlay" id="rulePanelOverlay" onclick="closeRulePanel()"></div>

    {{-- Panel --}}
    <div class="rule-panel" id="rulePanel" style="background: #fff;">

        {{-- Panel Header --}}
        <div class="rule-panel-header" style="background: #7664E4; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; border-bottom: none; height: auto;">
            <div class="rule-panel-header-info">
                <h3 id="panelTitle" style="margin: 0; font-size: 14px; font-weight: 700; color: #fff; line-height: 1.2; font-family: inherit;">Kelola Rules</h3>
                <p style="margin: 3px 0 0 0; display: flex; align-items: center; gap: 5px;">
                    <span id="panelMenuChip"
                        style="font-family:'Courier New',monospace;font-size:.72rem;background:rgba(255,255,255,0.18);color:#fff;padding:2px 8px;border-radius:4px"></span>
                    <span id="panelActionBadge" style="font-size:.65rem; font-weight:700; color:#fff; background:rgba(255,255,255,0.25); padding:2px 6px; border-radius:4px;"></span>
                </p>
            </div>
            <button class="rule-panel-close" onclick="closeRulePanel()" type="button"
                style="
                    width: 30px; height: 30px;
                    border-radius: 8px;
                    background: rgba(255,255,255,0.15);
                    border: none;
                    color: #fff;
                    cursor: pointer;
                    display: flex; align-items: center; justify-content: center;
                    transition: background 0.15s;
                    flex-shrink: 0;
                    padding: 0;
                    box-shadow: none;
                "
                onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                <i class="fas fa-xmark" style="color: #fff; font-size: 13px;"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="rule-panel-tabs" style="display: flex; border-bottom: 1px solid #e2e8f0; background: #f8fafc; flex-shrink: 0;">
            <button class="rule-tab-btn active" id="tabBtnList" onclick="switchTab('list')" type="button"
                style="
                    flex: 1; padding: 12px; font-size: 13px; font-weight: 600; color: #64748b; background: transparent; border: none; border-bottom: 2px solid transparent; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s;
                "
                onfocus="this.style.outline='none'">
                <i class="fas fa-list-ul"></i> Daftar User
                <span class="rule-tab-badge" id="tabCountBadge" style="background: #e2e8f0; color: #64748b; border-radius: 20px; padding: 1px 7px; font-size: 11px; font-weight: 700; min-width: 18px; text-align: center;">0</span>
            </button>
            <button class="rule-tab-btn" id="tabBtnAdd" onclick="switchTab('add')" type="button"
                style="
                    flex: 1; padding: 12px; font-size: 13px; font-weight: 600; color: #64748b; background: transparent; border: none; border-bottom: 2px solid transparent; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.2s;
                "
                onfocus="this.style.outline='none'">
                <i class="fas fa-user-plus"></i> Tambah Rule
            </button>
        </div>

        {{-- Tab: Daftar rules --}}
        <div class="rule-panel-body" style="background: #fff;">
            <div class="rule-panel-section active" id="sectionList" style="padding: 1.25rem;">
                <div id="ruleListContainer"></div>
            </div>

            {{-- Tab: Tambah rule --}}
            <div class="rule-panel-section" id="sectionAdd" style="padding: 1.25rem;">
                <form id="addRuleForm" method="POST" action="" style="margin: 0;">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div style="margin-bottom: 1rem;">
                        <label for="pf-name"
                               style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            <i class="fas fa-user" style="margin-right:4px"></i>Nama User
                        </label>
                        <input type="text" id="pf-name" name="name" placeholder="Masukan nama user"
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
                        >
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Isi jika ingin memberikan akses ke user tertentu saja</div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label for="pf-unit"
                               style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            <i class="fas fa-building" style="margin-right:4px"></i>Unit / Divisi
                        </label>
                        <input type="text" id="pf-unit" name="unit" placeholder="Masukan Unit"
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
                        >
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Kosongkan jika tidak perlu filter unit</div>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label for="pf-jabatan"
                               style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            <i class="fas fa-user-tie" style="margin-right:4px"></i>Jabatan
                        </label>
                        <input type="text" id="pf-jabatan" name="jabatan" placeholder="Masukan Jabatan"
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
                        >
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Kosongkan jika tidak perlu filter jabatan</div>
                    </div>

                    <div style="padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-top:.5rem">
                        <p style="font-size:.72rem;color:#64748b;margin:0;line-height:1.6">
                            <i class="fas fa-circle-info" style="color:#7664E4;margin-right:5px"></i>
                            Minimal isi satu field. Rule akan cocok jika semua field yang diisi sesuai dengan data user.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Panel Footer --}}
        <div class="rule-panel-footer" id="panelFooter" style="padding: 1rem 1.25rem; border-top: 1px solid #e2e8f0; background: #f8fafc; display: flex; gap: 8px; justify-content: flex-end; flex-shrink: 0;">
            <button type="button" onclick="closeRulePanel()"
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
                Tutup
            </button>
            <button type="button" id="btnSaveRule" onclick="submitAddRule()"
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
                    display: none;
                    align-items: center;
                    gap: 7px;
                    transition: background 0.15s, box-shadow 0.15s;
                "
                onmouseover="this.style.background='#6453d4'; this.style.boxShadow='0 4px 12px rgba(118,100,228,0.35)'"
                onmouseout="this.style.background='#7664E4'; this.style.boxShadow='none'">
                <i class="fas fa-plus"></i> Tambah Rule
            </button>
        </div>
    </div>

    {{-- MODAL — Tambah Permission --}}
    <div class="perm-modal-overlay" id="permModalOverlay" onclick="handleModalOverlayClick(event)">
        <div class="perm-modal" id="permModal" style="overflow: hidden; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); border: none; max-width: 480px; width: 100%;">

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
                        <i class="fas fa-shield-halved" style="color: #fff; font-size: 13px;"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle" style="
                            margin: 0;
                            font-size: 14px;
                            font-weight: 700;
                            color: #fff;
                            line-height: 1.2;
                            font-family: inherit;
                        ">Tambah Permission Baru</h3>
                        <p style="
                            margin: 0;
                            font-size: 11px;
                            color: rgba(255,255,255,0.7);
                            margin-top: 1px;
                        ">Kelola modul dan level akses sistem</p>
                    </div>
                </div>
                <button class="perm-modal-close" onclick="closePermModal()" type="button"
                    style="
                        width: 30px; height: 30px;
                        border-radius: 8px;
                        background: rgba(255,255,255,0.15);
                        border: none;
                        cursor: pointer;
                        display: flex; align-items: center; justify-content: center;
                        transition: background 0.15s;
                        flex-shrink: 0;
                        padding: 0;
                        box-shadow: none;
                    "
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                </button>
            </div>

            <form action="{{ route('permissions.store') }}" method="POST" id="permForm" style="padding: 1.375rem 1.25rem 1.25rem; margin: 0; background: #fff;">
                @csrf
                <div class="perm-modal-body" style="padding: 0 !important; margin-bottom: 1.375rem;">

                    {{-- Menu --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="inp-menu"
                               style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            Menu <span style="color: #ef4444;">*</span>
                        </label>
                        <input
                            type="text"
                            id="inp-menu"
                            name="menu"
                            placeholder="Masukan Menu"
                            required
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
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Nama menu/modul yang akan diatur aksesnya</div>
                    </div>

                    {{-- Action / Level Akses --}}
                    <div style="margin-bottom: 1rem;">
                        <label for="inp-action"
                               style="
                                   display: block;
                                   font-size: 11px;
                                   font-weight: 700;
                                   color: #475569;
                                   text-transform: uppercase;
                                   letter-spacing: 0.06em;
                                   margin-bottom: 5px;
                               ">
                            Action / Level Akses <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="position: relative;">
                            <select
                                id="inp-action"
                                name="action"
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
                                <option value="read">READ</option>
                                <option value="create">CREATE</option>
                                <option value="update">UPDATE</option>
                                <option value="delete">DELETE</option>
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

                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 1.25rem 0;">

                    {{-- Rules awal (opsional) --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.06em;">Rules awal (opsional)</span>
                        <button type="button" onclick="addModalRuleRow()"
                            style="
                                font-size: 11px !important;
                                color: #7664E4 !important;
                                background: none !important;
                                border: none !important;
                                cursor: pointer !important;
                                padding: 0 !important;
                                display: inline-flex !important;
                                align-items: center !important;
                                gap: 4px !important;
                                font-weight: 700 !important;
                                box-shadow: none !important;
                            "
                            onmouseover="this.style.opacity='0.8'"
                            onmouseout="this.style.opacity='1'">
                            <i class="fas fa-plus"></i> Tambah baris
                        </button>
                    </div>

                    <div id="modalRulesRows" style="display: flex; flex-direction: column; gap: 8px;">
                        <div class="modal-rule-row" style="display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr) 28px; gap: 5px; align-items: center;">
                            <input type="text" name="rules[0][name]" placeholder="Nama user"
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
                            >
                            <input type="text" name="rules[0][unit]" placeholder="Unit"
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
                            >
                            <input type="text" name="rules[0][jabatan]" placeholder="Jabatan"
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
                            >
                            <div></div>
                        </div>
                    </div>
                    
                    <div style="font-size: 11px; color: #64748b; margin-top: 8px;">
                        <i class="fas fa-info-circle"></i>
                        Kosongkan jika ingin semua user dapat mengakses. Rule bisa ditambah setelah disimpan.
                    </div>

                </div>

                {{-- Footer --}}
                <div style="
                    border-top: 1px solid #f1f5f9;
                    padding-top: 1rem;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 8px;
                ">
                    <button type="button" onclick="closePermModal()"
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

    {{-- MODAL — Edit Rule --}}
    <div class="perm-modal-overlay" id="editRuleModalOverlay" onclick="handleEditRuleOverlayClick(event)">
        <div class="perm-modal" id="editRuleModal" style="overflow: hidden; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.2); border: none; max-width: 480px; width: 100%;">

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
                        <i class="fas fa-pen-to-square" style="color: #fff; font-size: 13px;"></i>
                    </div>
                    <div>
                        <h3 id="editModalTitle" style="
                            margin: 0;
                            font-size: 14px;
                            font-weight: 700;
                            color: #fff;
                            line-height: 1.2;
                            font-family: inherit;
                        ">Edit Rule</h3>
                        <p style="
                            margin: 0;
                            font-size: 11px;
                            color: rgba(255,255,255,0.7);
                            margin-top: 1px;
                        ">Ubah filter hak akses untuk user</p>
                    </div>
                </div>
                <button class="perm-modal-close" onclick="closeEditRuleModal()" type="button"
                    style="
                        width: 30px; height: 30px;
                        border-radius: 8px;
                        background: rgba(255,255,255,0.15);
                        border: none;
                        cursor: pointer;
                        display: flex; align-items: center; justify-content: center;
                        transition: background 0.15s;
                        flex-shrink: 0;
                        padding: 0;
                        box-shadow: none;
                    "
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <i class="fas fa-times" style="color: #fff; font-size: 13px;"></i>
                </button>
            </div>

            <div class="perm-modal-body" style="padding: 1.375rem 1.25rem 1.25rem !important; margin: 0; background: #fff;">
                {{-- ID rule disimpan di sini --}}
                <input type="hidden" id="editRuleId">

                <div style="margin-bottom: 1rem;">
                    <label for="edit-name"
                           style="
                               display: block;
                               font-size: 11px;
                               font-weight: 700;
                               color: #475569;
                               text-transform: uppercase;
                               letter-spacing: 0.06em;
                               margin-bottom: 5px;
                           ">
                        <i class="fas fa-user" style="margin-right:4px"></i>Nama User
                    </label>
                    <input type="text" id="edit-name" placeholder="Nama user"
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
                    >
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Kosongkan jika tidak perlu filter nama</div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit-unit"
                           style="
                               display: block;
                               font-size: 11px;
                               font-weight: 700;
                               color: #475569;
                               text-transform: uppercase;
                               letter-spacing: 0.06em;
                               margin-bottom: 5px;
                           ">
                        <i class="fas fa-building" style="margin-right:4px"></i>Unit / Divisi
                    </label>
                    <input type="text" id="edit-unit" placeholder="Unit"
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
                    >
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Kosongkan jika tidak perlu filter unit</div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="edit-jabatan"
                           style="
                               display: block;
                               font-size: 11px;
                               font-weight: 700;
                               color: #475569;
                               text-transform: uppercase;
                               letter-spacing: 0.06em;
                               margin-bottom: 5px;
                           ">
                        <i class="fas fa-user-tie" style="margin-right:4px"></i>Jabatan
                    </label>
                    <input type="text" id="edit-jabatan" placeholder="Jabatan"
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
                    >
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Kosongkan jika tidak perlu filter jabatan</div>
                </div>

                <div style="padding:12px;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0;margin-top:.5rem">
                    <p style="font-size:.72rem;color:#64748b;margin:0;line-height:1.6">
                        <i class="fas fa-circle-info" style="color:#7664E4;margin-right:5px"></i>
                        Minimal isi satu field. Rule cocok jika semua field yang diisi sesuai data user.
                    </p>
                </div>

                {{-- Footer --}}
                <div style="
                    border-top: 1px solid #f1f5f9;
                    padding-top: 1rem;
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 8px;
                    margin-top: 1rem;
                ">
                    <button type="button" onclick="closeEditRuleModal()"
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
                    <button type="button" id="btnSaveEditRule" onclick="submitEditRule()"
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
                        <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Embed semua data rules sebagai JSON --}}
    <script id="permissionsData" type="application/json">
    {!! json_encode($permissions->map(function($p) {
        return [
            'id'     => $p->id,
            'menu'   => $p->menu,
            'action' => $p->action,
            'rules'  => $p->rules->map(fn($r) => [
                'id'      => $r->id,
                'unit'    => $r->unit    ?? '',
                'jabatan' => $r->jabatan ?? '',
                'name'    => $r->name    ?? '',
            ])->values()
        ];
    })->values()) !!}
</script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/permissions.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-center",
            timeOut: 3000,
        };

        window.deleteRuleUrl = "{{ url('/permissions/delete-rule') }}";
        window.updateRuleUrl = "{{ url('/permissions/update-rule') }}";

        let modalRuleIndex = 1;
        window.addModalRuleRow = function() {
            const container = document.getElementById('modalRulesRows');
            if (!container) return;
            
            const row = document.createElement('div');
            row.className = 'modal-rule-row';
            row.style.display = 'grid';
            row.style.gridTemplateColumns = 'minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr) 28px';
            row.style.gap = '5px';
            row.style.alignItems = 'center';
            row.style.marginBottom = '5px';
            
            row.innerHTML = `
                <input type="text" name="rules[` + modalRuleIndex + `][name]" placeholder="Nama user" style="width:100%; height:38px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:0 11px; font-size:13.5px; outline:none;" onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                <input type="text" name="rules[` + modalRuleIndex + `][unit]" placeholder="Unit" style="width:100%; height:38px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:0 11px; font-size:13.5px; outline:none;" onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                <input type="text" name="rules[` + modalRuleIndex + `][jabatan]" placeholder="Jabatan" style="width:100%; height:38px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:8px; padding:0 11px; font-size:13.5px; outline:none;" onfocus="this.style.borderColor='#7664E4'; this.style.boxShadow='0 0 0 3px rgba(118,100,228,0.12)'" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none'">
                <button type="button" class="btn-rm-row-modal" onclick="this.parentElement.remove()" style="width:28px; height:28px; border-radius:6px; border:1px solid #f0959b; background:transparent; color:#993c1d; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:0.75rem; padding:0; box-shadow:none;">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            container.appendChild(row);
            modalRuleIndex++;
        }
    </script>
@endpush
