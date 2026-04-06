@extends('layouts.app')

@section('title', 'Manajemen Hak Akses')

{{-- Styles --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/permissions.css') }}">
@endpush

@section('content')
    <div class="perm-page">

        {{-- ═══ Header ═══ --}}
        <div class="perm-header">
            <div class="perm-header-info">
                <h1><i class="fas fa-shield-halved"></i> Manajemen Hak Akses</h1>
                <p>Kelola permission dan aturan akses tiap modul sistem</p>
            </div>
            <button class="btn-add-perm" onclick="openPermModal()" type="button">
                <i class="fas fa-plus"></i> Tambah Permission
            </button>
        </div>

        {{-- ═══ Alerts ═══ --}}
        @if (session('success'))
            <div class="perm-alert perm-alert-success">
                <i class="fas fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="perm-alert perm-alert-error">
                <i class="fas fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ═══ Stats ═══ --}}
        <div class="perm-stats">
            <div class="perm-stat">
                <div class="perm-stat-label"><i class="fas fa-key"></i> Total permission</div>
                <div class="perm-stat-value">{{ $permissions->count() }}</div>
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

        {{-- ═══ Table Card ═══ --}}
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
                <tbody>
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
                        <tr>
                            {{-- Menu --}}
                            <td>
                                <span class="menu-chip" title="{{ $p->menu }}">{{ $p->menu }}</span>
                            </td>

                            {{-- Action --}}
                            <td>
                                <span class="action-badge {{ $badgeClass }}">
                                    <span class="dot"></span>
                                    {{ strtoupper($p->action) }}
                                </span>
                            </td>

                            {{-- Rules ringkas --}}
                            <td>
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
                            <td>
                                <div class="perm-action-btns">
                                    {{-- Tombol kelola rules — buka side panel --}}
                                    <button type="button" class="btn-manage-rules"
                                        onclick="openRulePanel(
                                        {{ $p->id }},
                                        '{{ addslashes($p->menu) }}',
                                        '{{ $p->action }}'
                                    )">
                                        <i class="fas fa-sliders"></i> Kelola
                                    </button>

                                    {{-- Hapus permission --}}
                                    <form action="{{ route('permissions.destroy', $p->id) }}" method="POST"
                                        style="display:inline;margin:0"
                                        onsubmit="return confirm('Yakin hapus permission {{ addslashes($p->menu) }} – {{ $p->action }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete-perm">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
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

    </div>{{-- end .perm-page --}}


    {{-- ═══════════════════════════════════════════════════════
     SIDE PANEL — Kelola Rules
     Data rules di-embed sebagai JSON untuk operasi client-side
     ═══════════════════════════════════════════════════════ --}}

    {{-- Overlay --}}
    <div class="rule-panel-overlay" id="rulePanelOverlay" onclick="closeRulePanel()"></div>

    {{-- Panel --}}
    <div class="rule-panel" id="rulePanel">

        {{-- Panel Header --}}
        <div class="rule-panel-header">
            <div class="rule-panel-header-info">
                <h3 id="panelTitle">Kelola Rules</h3>
                <p>
                    <span id="panelMenuChip"
                        style="font-family:'Courier New',monospace;font-size:.72rem;background:rgba(255,255,255,.2);padding:2px 8px;border-radius:4px"></span>
                    &nbsp;
                    <span id="panelActionBadge"></span>
                </p>
            </div>
            <button class="rule-panel-close" onclick="closeRulePanel()" type="button">
                <i class="fas fa-xmark"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="rule-panel-tabs">
            <button class="rule-tab-btn active" id="tabBtnList" onclick="switchTab('list')" type="button">
                <i class="fas fa-list-ul"></i> Daftar User
                <span class="rule-tab-badge" id="tabCountBadge">0</span>
            </button>
            <button class="rule-tab-btn" id="tabBtnAdd" onclick="switchTab('add')" type="button">
                <i class="fas fa-user-plus"></i> Tambah Rule
            </button>
        </div>

        {{-- Tab: Daftar rules --}}
        <div class="rule-panel-body">
            <div class="rule-panel-section active" id="sectionList">
                <div id="ruleListContainer"></div>
            </div>

            {{-- Tab: Tambah rule --}}
            <div class="rule-panel-section" id="sectionAdd">
                <form id="addRuleForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="_method" value="POST">

                    <div class="pf-group">
                        <label class="pf-label" for="pf-unit">
                            <i class="fas fa-building" style="margin-right:4px"></i>Unit / Divisi
                        </label>
                        <input type="text" id="pf-unit" name="unit" class="pf-input"
                            placeholder="Contoh: Teknologi Informasi, HRD, Finance">
                        <div class="pf-hint">Kosongkan jika tidak perlu filter unit</div>
                    </div>

                    <div class="pf-group">
                        <label class="pf-label" for="pf-jabatan">
                            <i class="fas fa-user-tie" style="margin-right:4px"></i>Jabatan
                        </label>
                        <input type="text" id="pf-jabatan" name="jabatan" class="pf-input"
                            placeholder="Contoh: Manager, Staff, Supervisor">
                        <div class="pf-hint">Kosongkan jika tidak perlu filter jabatan</div>
                    </div>

                    <div class="pf-group">
                        <label class="pf-label" for="pf-name">
                            <i class="fas fa-user" style="margin-right:4px"></i>Nama User (spesifik)
                        </label>
                        <input type="text" id="pf-name" name="name" class="pf-input"
                            placeholder="Contoh: Budi Santoso">
                        <div class="pf-hint">Isi jika ingin memberikan akses ke user tertentu saja</div>
                    </div>

                    <div
                        style="padding:12px;background:var(--surface2);border-radius:var(--r-md);border:1px solid var(--border);margin-top:.5rem">
                        <p style="font-size:.72rem;color:var(--muted);margin:0;line-height:1.6">
                            <i class="fas fa-circle-info" style="color:var(--accent);margin-right:5px"></i>
                            Minimal isi satu field. Rule akan cocok jika semua field yang diisi sesuai dengan data user.
                        </p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Panel Footer --}}
        <div class="rule-panel-footer" id="panelFooter">
            <button type="button" class="btn-pf-cancel" onclick="closeRulePanel()">Tutup</button>
            <button type="button" class="btn-pf-submit" id="btnSaveRule" onclick="submitAddRule()"
                style="display:none">
                <i class="fas fa-plus"></i> Tambah Rule
            </button>
        </div>

    </div>{{-- end .rule-panel --}}


    {{-- ═══════════════════════
     MODAL — Tambah Permission
     ═══════════════════════ --}}
    <div class="perm-modal-overlay" id="permModalOverlay" onclick="handleModalOverlayClick(event)">
        <div class="perm-modal" id="permModal">

            <div class="perm-modal-header">
                <h2><i class="fas fa-plus-circle" style="margin-right:7px;opacity:.85"></i>Tambah Permission Baru</h2>
                <button class="perm-modal-close" onclick="closePermModal()" type="button">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('permissions.store') }}" method="POST" id="permForm">
                @csrf
                <div class="perm-modal-body">

                    <div class="perm-form-group">
                        <label class="perm-form-label" for="inp-menu">Menu</label>
                        <input type="text" id="inp-menu" name="menu" class="perm-form-input"
                            placeholder="Contoh: dashboard, helpdesk, report" required>
                        <div class="perm-form-hint">Nama menu/modul yang akan diatur aksesnya</div>
                    </div>

                    <div class="perm-form-group">
                        <label class="perm-form-label" for="inp-action">Action / Level Akses</label>
                        <select id="inp-action" name="action" class="perm-form-select">
                            <option value="read">READ</option>
                            <option value="create">CREATE</option>
                            <option value="update">UPDATE</option>
                            <option value="delete">DELETE</option>
                        </select>
                    </div>

                    <hr class="perm-form-divider">

                    <div class="perm-form-section-head">
                        <span class="perm-form-section-label">Rules awal (opsional)</span>
                        <button type="button" class="btn-add-row-modal" onclick="addModalRuleRow()">
                            <i class="fas fa-plus"></i> Tambah baris
                        </button>
                    </div>
                    <div class="modal-rules-rows" id="modalRulesRows">
                        <div class="modal-rule-row">
                            <input type="text" name="rules[0][unit]" placeholder="Unit">
                            <input type="text" name="rules[0][jabatan]" placeholder="Jabatan">
                            <input type="text" name="rules[0][name]" placeholder="Nama user">
                            <div></div>
                        </div>
                    </div>
                    <div class="perm-form-hint" style="margin-top:8px">
                        <i class="fas fa-circle-info"></i>
                        Kosongkan jika ingin semua user dapat mengakses. Rule bisa ditambah setelah disimpan.
                    </div>

                </div>
                <div class="perm-modal-footer">
                    <button type="button" class="btn-perm-cancel" onclick="closePermModal()">Batal</button>
                    <button type="submit" class="btn-perm-submit">
                        <i class="fas fa-floppy-disk"></i> Simpan Permission
                    </button>
                </div>
            </form>
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
    <script>
        (function() {
            'use strict';

            /* ── Data ── */
            var permissionsData = JSON.parse(document.getElementById('permissionsData').textContent);
            var activePerm = null;
            var activeTab = 'list';
            var ruleCounter = 1;

            /* ═══ SIDE PANEL ═══ */
            window.openRulePanel = function(permId, menu, action) {
                activePerm = permissionsData.find(function(p) {
                    return p.id === permId;
                });
                if (!activePerm) return;

                document.getElementById('panelTitle').textContent = 'Kelola Rules — ' + menu;
                document.getElementById('panelMenuChip').textContent = menu;
                document.getElementById('panelActionBadge').textContent = action.toUpperCase();

                var addRuleForm = document.getElementById('addRuleForm');
                addRuleForm.action = '/permissions/' + permId + '/add-rule';

                switchTab('list');
                renderRuleList();

                document.getElementById('rulePanelOverlay').classList.add('open');
                document.getElementById('rulePanel').classList.add('open');
            };

            window.closeRulePanel = function() {
                document.getElementById('rulePanelOverlay').classList.remove('open');
                document.getElementById('rulePanel').classList.remove('open');
                activePerm = null;
                clearAddForm();
            };

            window.switchTab = function(tab) {
                activeTab = tab;
                document.getElementById('tabBtnList').classList.toggle('active', tab === 'list');
                document.getElementById('tabBtnAdd').classList.toggle('active', tab === 'add');
                document.getElementById('sectionList').classList.toggle('active', tab === 'list');
                document.getElementById('sectionAdd').classList.toggle('active', tab === 'add');
                document.getElementById('btnSaveRule').style.display = tab === 'add' ? 'inline-flex' : 'none';
            };

            function renderRuleList() {
    var container = document.getElementById('ruleListContainer');
    var rules = activePerm ? activePerm.rules : [];

    document.getElementById('tabCountBadge').textContent = rules.length;

    if (rules.length === 0) {
        container.innerHTML =
            '<div class="rule-list-empty">' +
            '<i class="fas fa-users-slash"></i>' +
            '<p>Belum ada rule.<br>Semua user dapat mengakses menu ini.</p>' +
            '</div>';
        return;
    }

    container.innerHTML = rules.map(function(r) {
        var parts = [];
        
        // ✅ UNIT - HURUF BESAR SEMUA
        if (r.unit) parts.push('<span class="rule-meta-chip"><i class="fas fa-building"></i>' + 
            esc(r.unit.toUpperCase()) + '</span>');  // <-- tambah .toUpperCase()
        
        // ✅ JABATAN - HURUF BESAR SEMUA
        if (r.jabatan) parts.push('<span class="rule-meta-chip"><i class="fas fa-user-tie"></i>' + 
            esc(r.jabatan.toUpperCase()) + '</span>');  // <-- tambah .toUpperCase()

        var icon = r.unit ? 'fa-building' : (r.jabatan ? 'fa-user-tie' : 'fa-user');
        
        // ✅ NAMA USER - Format: raden.ibnu -> Ibnu Raden
        var formattedName = '';
        if (r.name) {
            formattedName = formatUserName(r.name);
        }
        
        var nameRow = formattedName ?
            '<div class="rule-name-main"><i class="fas fa-user" style="font-size:10px;margin-right:3px;color:var(--muted)"></i>' +
            esc(formattedName) + '</div>' :
            '';

        return '<div class="rule-list-item" id="rule-item-' + r.id + '">' +
            '<div class="rule-list-icon"><i class="fas ' + icon + '"></i></div>' +
            '<div class="rule-list-meta">' +
            (parts.length ? '<div class="rule-list-meta-row">' + parts.join(
                '<span class="rule-meta-sep">·</span>') + '</div>' : '') +
            nameRow +
            (!parts.length && !r.name ?
                '<span style="font-size:.72rem;color:var(--hint);font-style:italic">Rule tanpa detail</span>' :
                '') +
            '</div>' +
            '<form action="/permissions/delete-rule/' + r.id +
            '" method="POST" style="display:inline;margin:0">' +
            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
            '<input type="hidden" name="_method" value="DELETE">' +
            '<button type="submit" class="btn-rule-delete" title="Hapus rule" ' +
            'onclick="return confirm(\'Hapus rule ini?\')">' +
            '<i class="fas fa-trash-can"></i>' +
            '</button>' +
            '</form>' +
            '</div>';
    }).join('');
}

// ✅ TAMBAHKAN FUNGSI INI untuk format nama user
function formatUserName(name) {
    if (!name) return '';
    
    // Jika nama mengandung titik (format: raden.ibnu)
    if (name.includes('.')) {
        return name.split('.')
            .map(function(part) {
                return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
            })
            .join(' ');
    }
    
    // Jika sudah dalam format normal, tetap capitalize tiap kata
    return name.split(' ')
        .map(function(word) {
            return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        })
        .join(' ');
}

            window.submitAddRule = function() {
                var unit = document.getElementById('pf-unit').value.trim();
                var jabatan = document.getElementById('pf-jabatan').value.trim();
                var name = document.getElementById('pf-name').value.trim();
                if (!unit && !jabatan && !name) {
                    alert('Isi minimal satu field (Unit, Jabatan, atau Nama User).');
                    return;
                }
                document.getElementById('addRuleForm').submit();
            };

            function clearAddForm() {
                document.getElementById('pf-unit').value = '';
                document.getElementById('pf-jabatan').value = '';
                document.getElementById('pf-name').value = '';
            }

            function esc(str) {
                return String(str)
                    .replace(/&/g, '&amp;').replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }

            /* ═══ MODAL Tambah Permission ═══ */
            window.openPermModal = function() {
                document.getElementById('permModalOverlay').classList.add('open');
                setTimeout(function() {
                    document.getElementById('inp-menu').focus();
                }, 80);
            };
            window.closePermModal = function() {
                document.getElementById('permModalOverlay').classList.remove('open');
                resetPermForm();
            };
            window.handleModalOverlayClick = function(e) {
                if (e.target === document.getElementById('permModalOverlay')) closePermModal();
            };

            function resetPermForm() {
                document.getElementById('permForm').reset();
                document.getElementById('modalRulesRows').innerHTML =
                    '<div class="modal-rule-row">' +
                    '<input type="text" name="rules[0][unit]"    placeholder="Unit">' +
                    '<input type="text" name="rules[0][jabatan]" placeholder="Jabatan">' +
                    '<input type="text" name="rules[0][name]"    placeholder="Nama user">' +
                    '<div></div>' +
                    '</div>';
                ruleCounter = 1;
            }
            window.addModalRuleRow = function() {
                var c = document.getElementById('modalRulesRows');
                var d = document.createElement('div');
                d.className = 'modal-rule-row';
                d.innerHTML =
                    '<input type="text" name="rules[' + ruleCounter + '][unit]"    placeholder="Unit">' +
                    '<input type="text" name="rules[' + ruleCounter + '][jabatan]" placeholder="Jabatan">' +
                    '<input type="text" name="rules[' + ruleCounter + '][name]"    placeholder="Nama user">' +
                    '<button type="button" class="btn-rm-row-modal" ' +
                    'onclick="this.closest(\'.modal-rule-row\').remove()">' +
                    '<i class="fas fa-xmark"></i>' +
                    '</button>';
                c.appendChild(d);
                d.querySelector('input').focus();
                ruleCounter++;
            };

            /* ═══ ESC ═══ */
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Escape') return;
                if (document.getElementById('rulePanel').classList.contains('open')) {
                    closeRulePanel();
                    return;
                }
                if (document.getElementById('permModalOverlay').classList.contains('open')) {
                    closePermModal();
                }
            });

            /* ═══ Auto-dismiss alerts ═══ */
            document.querySelectorAll('.perm-alert').forEach(function(el) {
                setTimeout(function() {
                    el.style.transition = 'opacity .5s';
                    el.style.opacity = '0';
                    setTimeout(function() {
                        el.remove();
                    }, 500);
                }, 4000);
            });

        })();
    </script>
@endpush
