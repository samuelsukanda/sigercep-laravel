@extends('layouts.app')

@section('title', 'SIGERCEP - Credential Hardware')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- Header --}}
                <x-page-header icon="fa-key" title="Credential Hardware" subtitle="Kelola kredensial hardware" />

                {{-- Filter --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="px-5 py-4">
                        <div class="flex flex-wrap gap-3 items-end justify-between">
                            <div class="flex flex-wrap gap-3 items-end">
                                {{-- Cari + Reset --}}
                                <div class="flex flex-col mr-1" style="min-width:280px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Cari</label>
                                    <div class="flex gap-2 items-center">
                                        <input type="text" id="filterCari" placeholder="Nama PC / IP / Unit"
                                            class="flex-1 mr-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                            oninput="gantiFilter()">
                                        <button type="button" onclick="resetFilter()"
                                            class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-slate-700 uppercase rounded-lg shadow-md bg-gray-200 hover:shadow-sm active:opacity-85 transition-all">
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="button" onclick="bukaModalTambah()"
                                    class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-white uppercase rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                                    style="background-color: var(--accent) !important;">
                                    Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konten --}}
                <div id="kontenCredential">
                    {{-- Diisi oleh JS --}}
                </div>

                {{-- Pagination --}}
                <div id="paginationNav" style="display:none;"></div>

                {{-- Empty State --}}
                <div id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
                    <div
                        style="width:72px; height:72px; border-radius:50%; background:#f3f4f6;
                                display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <i class="fas fa-key" style="font-size:28px; color:#d1d5db;"></i>
                    </div>
                    <h5 style="color:#374151; font-weight:600; margin-bottom:8px;">Belum Ada Data Credential</h5>
                    <p style="color:#9ca3af; font-size:13px;">Klik <b>Tambah Data</b> untuk menyimpan akun &amp; password
                        user per PC.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .btn-swal-confirm {
            background-color: #ef4444 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-confirm:hover {
            background-color: #dc2626 !important;
        }

        .btn-swal-cancel {
            background-color: #6b7280 !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-cancel:hover {
            background-color: #4b5563 !important;
        }

        .btn-swal-success {
            background-color: var(--accent) !important;
            color: #ffffff !important;
            transition: background-color 0.2s !important;
        }

        .btn-swal-success:hover {
            background-color: #6051c9 !important;
        }

        /* Pastikan SweetAlert selalu tampil di atas semua modal */
        .swal2-container {
            z-index: 99999 !important;
        }

        .swal2-backdrop-show {
            z-index: 99998 !important;
        }
    </style>
    <script>
        var semuaDataGlobal = [];
        var daftarPc = @json($daftarPc);
        var edittingId = null;
        var halamanSekarang = 1;
        var limitPerHalaman = 10;

        $(document).ready(function() {
            bangunOpsiPc();
            $('#selectPc').select2({
                placeholder: 'Pilih PC',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modalCredential')
            });
            muatSemuaData();
        });

        // ===== LOAD & RENDER =====
        function muatSemuaData() {
            var konten = document.getElementById('kontenCredential');
            konten.innerHTML =
                '<div style="text-align:center;padding:40px;color:#9ca3af;"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i><p style="margin-top:12px;font-size:13px;">Memuat data credential...</p></div>';

            fetch('{{ route('hardware.credential.data') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    semuaDataGlobal = json.data || [];
                    halamanSekarang = 1;
                    renderSemua();
                })
                .catch(function() {
                    konten.innerHTML =
                        '<div style="text-align:center;padding:40px;color:#ef4444;font-size:13px;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data.</div>';
                });
        }

        function filterSemua() {
            var semua = semuaDataGlobal;
            var cari = (document.getElementById('filterCari').value || '').toLowerCase().trim();

            if (cari) {
                semua = semua.filter(function(item) {
                    return (item.nama_pc || '').toLowerCase().indexOf(cari) >= 0 ||
                        (item.ip || '').toLowerCase().indexOf(cari) >= 0 ||
                        (item.unit || '').toLowerCase().indexOf(cari) >= 0;
                });
            }
            return semua;
        }

        function renderSemua() {
            var semua = filterSemua();
            var konten = document.getElementById('kontenCredential');
            var empty = document.getElementById('emptyState');
            var nav = document.getElementById('paginationNav');

            if (semua.length === 0) {
                konten.innerHTML = '';
                empty.style.display = 'block';
                nav.style.display = 'none';
                nav.innerHTML = '';
                return;
            }

            empty.style.display = 'none';

            var totalHalaman = Math.ceil(semua.length / limitPerHalaman);
            if (halamanSekarang > totalHalaman) halamanSekarang = totalHalaman;
            if (halamanSekarang < 1) halamanSekarang = 1;

            var mulai = (halamanSekarang - 1) * limitPerHalaman;
            var potongan = semua.slice(mulai, mulai + limitPerHalaman);

            var html = '';
            potongan.forEach(function(item) {
                html += cardHtml(item);
            });
            konten.innerHTML = html;
            renderPagination(semua.length, totalHalaman);
        }

        function gantiFilter() {
            halamanSekarang = 1;
            renderSemua();
        }

        function resetFilter() {
            document.getElementById('filterCari').value = '';
            halamanSekarang = 1;
            renderSemua();
        }

        function pindahHalaman(hal) {
            if (hal < 1) return;
            halamanSekarang = hal;
            renderSemua();
        }

        function renderPagination(total, totalHalaman) {
            var nav = document.getElementById('paginationNav');
            if (totalHalaman <= 1) {
                nav.style.display = 'none';
                nav.innerHTML = '';
                return;
            }

            nav.style.display = 'flex';
            nav.style.justifyContent = 'space-between';
            nav.style.alignItems = 'center';
            nav.style.flexWrap = 'wrap';
            nav.style.gap = '10px';
            nav.style.marginTop = '8px';

            var info = '<div style="font-size:12px;color:#6b7280;">Menampilkan <b>' + total +
                '</b> data &middot; Halaman <b>' + halamanSekarang + '/' + totalHalaman + '</b></div>';

            var btn = function(label, hal, disabled, active) {
                var style =
                    'display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;' +
                    'padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;' +
                    'background:#fff;color:#374151;cursor:pointer;';
                if (disabled) style += 'opacity:0.4;cursor:not-allowed;';
                if (active) style += 'background:var(--accent);border-color:var(--accent);color:#fff;';
                var onclick = disabled ? '' : ' onclick="pindahHalaman(' + hal + ')"';
                return '<button type="button" style="' + style + '"' + onclick + '>' + label + '</button>';
            };

            var angka = '';
            var mulaiH = Math.max(1, halamanSekarang - 2);
            var akhirH = Math.min(totalHalaman, mulaiH + 4);
            mulaiH = Math.max(1, akhirH - 4);
            for (var i = mulaiH; i <= akhirH; i++) {
                angka += btn(i, i, false, i === halamanSekarang);
            }

            nav.innerHTML = info +
                '<div style="display:flex;gap:6px;flex-wrap:wrap;">' +
                btn('<i class="fas fa-chevron-left"></i>', halamanSekarang - 1, halamanSekarang === 1, false) +
                angka +
                btn('<i class="fas fa-chevron-right"></i>', halamanSekarang + 1, halamanSekarang === totalHalaman, false) +
                '</div>';
        }

        function toggleKartu(header) {
            var body = header.nextElementSibling;
            var chevron = header.querySelector('.kartu-chevron');
            if (!body || !chevron) return;
            if (body.style.display === 'none') {
                body.style.display = 'block';
                chevron.style.transform = 'rotate(180deg)';
            } else {
                body.style.display = 'none';
                chevron.style.transform = 'rotate(0deg)';
            }
        }

        function escapeHtml(text) {
            return String(text == null ? '' : text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function maskPw(value) {
            return value ? '••••••••' : '-';
        }

        function escapeAttr(text) {
            return escapeHtml(text).replace(/'/g, '&#39;');
        }

        function cardHtml(item) {
            var jumlahAkun = item.items.length;
            var showAll = window._semuaPwTerlihat === true;

            var barisHtml = '';
            item.items.forEach(function(row, i) {
                var pwText = row.password ? (showAll ? escapeHtml(row.password) : maskPw(row.password)) : '-';
                barisHtml += `
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:9px 16px; text-align:center; font-weight:600; color:#9ca3af; font-size:12px; width:44px;">${i + 1}</td>
                    <td style="padding:9px 16px; font-size:12.5px; color:#1f2937; font-weight:600; white-space:nowrap;">${escapeHtml(row.nama || '-')}</td>
                    <td style="padding:9px 16px; font-size:12.5px; color:#374151; white-space:nowrap;">${escapeHtml(row.username || '-')}</td>
                    <td class="cell-pw" data-pw="${row.password ? escapeAttr(row.password) : ''}"
                        style="padding:9px 16px; font-size:12.5px; color:#374151; white-space:nowrap; font-family:monospace; letter-spacing:${row.password && showAll ? '0px' : '1px'};">${pwText}</td>
                    <td style="padding:9px 16px; font-size:12px; color:#6b7280;">${escapeHtml(row.notes || '-')}</td>
                </tr>`;
            });

            return `
            <div style="background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08);
                        border:1px solid #e5e7eb; margin-bottom:16px; overflow:hidden;">
                <div onclick="toggleKartu(this)" style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
                            padding:14px 20px; background:linear-gradient(135deg,var(--accent-grad-1) 0%,var(--accent-grad-3) 100%);">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <i class="fas fa-chevron-down kartu-chevron" style="color:#fff; font-size:12px; transition:transform 0.2s;"></i>
                        <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.2);
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-desktop" style="color:#fff; font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:700; color:#fff;">${escapeHtml(item.nama_pc)}</div>
                            <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap; font-size:11px; color:rgba(255,255,255,0.8); margin-top:4px;">
                                <i class="fas fa-network-wired" style="margin-right:2px;"></i>${escapeHtml(item.ip || '-')}
                                <span style="margin:0 2px;">|</span>
                                <i class="fas fa-building" style="margin-right:2px;"></i>${escapeHtml(item.unit || '-')}
                                <span style="margin:0 2px;">|</span>
                                <i class="fas fa-layer-group" style="margin-right:2px;"></i>${escapeHtml(item.lantai || '-')}
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                        <div style="text-align:right;">
                            <div style="font-size:12px; color:#fff; font-weight:600;">${jumlahAkun} akun</div>
                            <div style="font-size:10px; color:rgba(255,255,255,0.7);">
                                <i class="fas fa-clock" style="margin-right:3px;"></i>${escapeHtml(item.updated_at_formatted)}
                            </div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button onclick="event.stopPropagation(); editCredential(${item.id})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                       font-size:11px; font-weight:600; color:#3b82f6; border:none; cursor:pointer; border-radius:6px; background:#fff;
                                       transition:background 0.18s, color 0.18s;"
                                onmouseover="this.style.background='#eff6ff'; this.style.color='#2563eb';"
                                onmouseout="this.style.background='#fff'; this.style.color='#3b82f6';">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="event.stopPropagation(); hapusCredential(${item.id})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                       font-size:11px; font-weight:600; color:#ef4444; border:none; cursor:pointer; border-radius:6px; background:#fff;
                                       transition:background 0.18s, color 0.18s;"
                                onmouseover="this.style.background='#fef2f2'; this.style.color='#dc2626';"
                                onmouseout="this.style.background='#fff'; this.style.color='#ef4444';">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <div style="display:none;">
                    <div style="padding:10px 20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; border-bottom:1px solid #f3f4f6;">
                        <span style="font-size:11px; color:#9ca3af; font-weight:600;"><i class="fas fa-key" style="margin-right:5px;"></i>Daftar akun &amp; password user</span>
                        <span style="font-size:11px; color:#9ca3af;">Klik ikon mata untuk melihat password</span>
                    </div>
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f9fafb;">
                                    <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; width:44px;">No</th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Nama</th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Username</th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">
                                        Password
                                        <button type="button" onclick="toggleSemuaPassword()" title="Tampilkan / sembunyikan semua"
                                            style="background:none;border:none;cursor:pointer;color:#6b7280;font-size:11px;padding:0;margin-left:4px;">
                                            <i class="fas ${window._semuaPwTerlihat ? 'fa-eye-slash' : 'fa-eye'}"></i>
                                        </button>
                                    </th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Notes</th>
                                </tr>
                            </thead>
                            <tbody>${barisHtml}</tbody>
                        </table>
                    </div>
                </div>
            </div>`;
        }

        function toggleSemuaPassword() {
            var show = !(window._semuaPwTerlihat === true);
            window._semuaPwTerlihat = show;

            document.querySelectorAll('td.cell-pw').forEach(function(td) {
                var pw = td.getAttribute('data-pw') || '';
                var has = pw !== '';
                td.innerHTML = show ? (has ? pw : '-') : (has ? maskPw(pw) : '-');
                td.style.letterSpacing = (show && has) ? '0px' : '1px';
            });

            document.querySelectorAll('th button[onclick="toggleSemuaPassword()"] i').forEach(function(icon) {
                icon.className = show ? 'fas fa-eye-slash' : 'fas fa-eye';
            });
        }

        // ===== MODAL TAMBAH / EDIT =====
        function bangunOpsiPc() {
            var select = document.getElementById('selectPc');
            select.innerHTML = '';
            if (!daftarPc.length) {
                var opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'Belum ada PC terdaftar di master data';
                select.appendChild(opt);
                return;
            }

            var byJenis = {};
            daftarPc.forEach(function(pc) {
                if (!byJenis[pc.jenis]) byJenis[pc.jenis] = [];
                byJenis[pc.jenis].push(pc);
            });

            Object.keys(byJenis).forEach(function(jenis) {
                var group = document.createElement('optgroup');
                group.label = jenis;
                byJenis[jenis].forEach(function(pc) {
                    var opt = document.createElement('option');
                    opt.value = pc.nama_pc;
                    opt.textContent = pc.nama_pc + '  (' + (pc.ip || 'no IP') + ')';
                    opt.setAttribute('data-ip', pc.ip || '');
                    opt.setAttribute('data-unit', pc.unit || '');
                    opt.setAttribute('data-lantai', pc.lantai || '');
                    opt.setAttribute('data-jenis', pc.jenis);
                    group.appendChild(opt);
                });
                select.appendChild(group);
            });
        }

        function setPc(namaPc) {
            $('#selectPc').val(namaPc).trigger('change');
        }

        function onPcChange() {
            var select = document.getElementById('selectPc');
            var opt = select.options[select.selectedIndex];
            if (!opt) return;
            document.getElementById('fieldIp').value = opt.getAttribute('data-ip') || '';
            document.getElementById('fieldUnit').value = opt.getAttribute('data-unit') || '';
            document.getElementById('fieldLantai').value = opt.getAttribute('data-lantai') || '';
            var infoElem = document.getElementById('infoJenis');
            if (infoElem) infoElem.textContent = opt.getAttribute('data-jenis') || '';
        }

        function bukaModalTambah() {
            edittingId = null;
            document.getElementById('modalJudul').textContent = 'Tambah Credential';
            document.getElementById('modalSubJudul').textContent =
                'Simpan akun & password user untuk setiap PC secara terstruktur.';
            setPc('');
            renderBaris([]);
            resetBtnSimpan();
            document.getElementById('modalCredential').style.display = 'flex';
        }

        function editCredential(id) {
            edittingId = id;
            fetch('{{ route('hardware.credential.index') }}' + '/items/' + id, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    var rec = json.record;
                    document.getElementById('modalJudul').textContent = 'Edit Credential';
                    document.getElementById('modalSubJudul').textContent =
                        'Perbarui akun & password untuk ' + rec.nama_pc + '.';

                    setPc(rec.nama_pc);
                    document.getElementById('fieldIp').value = rec.ip || '';
                    document.getElementById('fieldUnit').value = rec.unit || '';
                    document.getElementById('fieldLantai').value = rec.lantai || '';
                    var infoElem = document.getElementById('infoJenis');
                    if (infoElem) infoElem.textContent = rec.jenis || '';

                    renderBaris(json.items || []);
                    resetBtnSimpan();
                    document.getElementById('modalCredential').style.display = 'flex';
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data tidak dapat dimuat.',
                        zIndex: 20000,
                        customClass: {
                            confirmButton: 'btn-swal-success'
                        }
                    });
                });
        }

        function tutupModal() {
            resetBtnSimpan();
            document.getElementById('modalCredential').style.display = 'none';
        }

        function resetBtnSimpan() {
            var btn = document.querySelector('[onclick="simpanCredential()"]');
            if (!btn) return;
            btn.innerHTML = '<i class="fas fa-save"></i> Simpan';
            btn.style.background = 'var(--accent)';
            btn.disabled = false;
        }

        function renderBaris(items) {
            var tbody = document.getElementById('tabelBarisBody');
            tbody.innerHTML = '';

            if (!items.length) {
                items = [{}];
            }

            items.forEach(function(data, i) {
                tbody.appendChild(buatBaris(data, i));
            });
        }

        function buatBaris(data, i) {
            data = data || {};
            var tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding:8px;">
                    <input type="text" name="nama" placeholder="Nama (mis. Admin)"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        value="${escapeHtml(data.nama || '')}">
                </td>
                <td style="padding:8px;">
                    <input type="text" name="username" placeholder="Username"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        value="${escapeHtml(data.username || '')}">
                </td>
                <td style="padding:8px;">
                    <div style="position:relative;">
                        <input type="text" name="password" placeholder="Password" autocomplete="off"
                            class="w-full px-2 py-2 pr-8 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                            value="${escapeHtml(data.password || '')}">
                        <button type="button" onclick="togglePwInput(this)"
                            style="position:absolute; right:8px; top:50%; transform:translateY(-50%); background:none;border:none;cursor:pointer;color:#9ca3af;font-size:12px;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </td>
                <td style="padding:8px;">
                    <input type="text" name="notes" placeholder="Keterangan (opsional)"
                        class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        value="${escapeHtml(data.notes || '')}">
                </td>
                <td style="padding:8px; text-align:center; white-space:nowrap;">
                    <button type="button" onclick="hapusBaris(this)" title="Hapus baris"
                        style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:14px;">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>`;
            return tr;
        }

        function tambahBaris() {
            var tbody = document.getElementById('tabelBarisBody');
            tbody.appendChild(buatBaris({}, tbody.children.length));
        }

        function hapusBaris(btn) {
            var tbody = document.getElementById('tabelBarisBody');
            var tr = btn.closest('tr');
            if (tbody.children.length <= 1) return;
            tr.remove();
        }

        function togglePwInput(btn) {
            var input = btn.parentElement.querySelector('input[name="password"]');
            if (!input) return;
            var icon = btn.querySelector('i');
            if (input.type === 'text') {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            } else {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            }
        }

        function collectRows() {
            var rows = [];
            document.querySelectorAll('#tabelBarisBody tr').forEach(function(tr) {
                rows.push({
                    nama: (tr.querySelector('input[name="nama"]').value || '').trim(),
                    username: (tr.querySelector('input[name="username"]').value || '').trim(),
                    password: (tr.querySelector('input[name="password"]').value || ''),
                    notes: (tr.querySelector('input[name="notes"]').value || '').trim()
                });
            });
            return rows;
        }

        function simpanCredential() {
            var namaPc = $('#selectPc').val();
            if (!namaPc) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih PC terlebih dahulu.',
                    zIndex: 20000,
                    customClass: {
                        confirmButton: 'btn-swal-success'
                    }
                });
                return;
            }

            var rows = collectRows().filter(function(r) {
                return r.nama || r.username || r.password;
            });
            if (!rows.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Minimal isi satu baris akun.',
                    zIndex: 20000,
                    customClass: {
                        confirmButton: 'btn-swal-success'
                    }
                });
                return;
            }

            var payload = {
                id: edittingId,
                nama_pc: namaPc,
                ip: document.getElementById('fieldIp').value,
                unit: document.getElementById('fieldUnit').value,
                lantai: document.getElementById('fieldLantai').value,
                rows: rows
            };

            var btn = document.querySelector('[onclick="simpanCredential()"]');
            var originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            fetch('{{ route('hardware.credential.simpan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    if (json.success) {
                        btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
                        btn.style.background = '#22c55e';
                        tutupModal();
                        muatSemuaData();
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                        btn.style.background = 'var(--accent)';
                    }
                })
                .catch(function() {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                    btn.style.background = 'var(--accent)';
                });
        }

        function hapusCredential(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data credential ini?',
                icon: 'warning',
                showCancelButton: true,
                zIndex: 20000,
                customClass: {
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                },
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    fetch('{{ route('hardware.credential.index') }}' + '/' + id, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(res) {
                            return res.json();
                        })
                        .then(function(json) {
                            if (json.success) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Data berhasil dihapus.',
                                    icon: 'success',
                                    zIndex: 20000,
                                    customClass: {
                                        confirmButton: 'btn-swal-success'
                                    }
                                });
                                halamanSekarang = 1;
                                muatSemuaData();
                            }
                        })
                        .catch(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Data tidak dapat dihapus.',
                                zIndex: 20000,
                                customClass: {
                                    confirmButton: 'btn-swal-success'
                                }
                            });
                        });
                }
            });
        }
    </script>
@endpush

@push('modals')
    <div id="modalCredential"
        style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:9999;
           background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
        <div
            style="background:#fff; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,0.25);
                width:100%; max-width:960px; max-height:92vh; display:flex; flex-direction:column;
                margin:auto;">
            {{-- Modal Header --}}
            <div
                style="display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-radius:16px 16px 0 0; background:var(--accent);">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.18);
                            display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-key" style="color:#fff; font-size:14px;"></i>
                    </div>
                    <div>
                        <h5 id="modalJudul" style="margin:0; font-size:15px; font-weight:700; color:#fff; line-height:1.2;">
                            Tambah Credential</h5>
                        <p id="modalSubJudul"
                            style="margin:0; font-size:11px; color:rgba(255,255,255,0.8); margin-top:2px;">Simpan akun
                            &amp; password user untuk setiap PC secara terstruktur.</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModal()"
                    style="width:32px; height:32px; border:none; background:rgba(255,255,255,0.15); cursor:pointer;
                       border-radius:8px; display:flex; align-items:center; justify-content:center;
                       color:#fff; font-size:14px; transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Informasi PC --}}
            <div style="padding:12px 24px; border-bottom:1px solid #f3f4f6; background:#f9fafb;">
                <div style="display:flex; flex-wrap:wrap; gap:14px;">
                    <div style="flex:1; min-width:220px;">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih PC</label>
                        <select id="selectPc"
                            class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all"
                            onchange="onPcChange()"></select>
                    </div>
                </div>
                <input type="hidden" id="fieldIp">
                <input type="hidden" id="fieldUnit">
                <input type="hidden" id="fieldLantai">
            </div>

            {{-- Daftar Akun --}}
            <div
                style="padding:12px 24px 8px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                <div>
                    <div style="font-size:13px; font-weight:700; color:#1f2937;">Daftar Akun &amp; Password</div>
                    <div style="font-size:11px; color:#9ca3af; margin-top:2px;">Isi akun (nama, username, password) untuk
                        PC ini.</div>
                </div>
                <button type="button" onclick="tambahBaris()"
                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 14px;
                           font-size:12px; font-weight:600; color:var(--accent); border:1px solid var(--accent); cursor:pointer;
                           border-radius:8px; background:#fff; transition:background 0.15s;"
                    onmouseover="this.style.background='#f5f3ff'" onmouseout="this.style.background='#fff'">
                    <i class="fas fa-plus"></i> Tambah Akun
                </button>
            </div>

            <div style="flex:1; overflow-y:auto; min-height:0; padding:0 24px;">
                <table style="width:100%; table-layout:fixed; border-collapse:collapse; font-size:13px;">
                    <colgroup>
                        <col style="width:130px;">
                        <col style="width:150px;">
                        <col style="width:170px;">
                        <col style="width:170px;">
                        <col style="width:44px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th
                                style="color:#6b7280; text-align:left; font-weight:700; padding:8px 8px 8px 0; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">
                                Nama</th>
                            <th
                                style="color:#6b7280; text-align:left; font-weight:700; padding:8px; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">
                                Username</th>
                            <th
                                style="color:#6b7280; text-align:left; font-weight:700; padding:8px; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">
                                Password</th>
                            <th
                                style="color:#6b7280; text-align:left; font-weight:700; padding:8px; font-size:11px; text-transform:uppercase; letter-spacing:0.05em;">
                                Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tabelBarisBody"></tbody>
                </table>
            </div>

            {{-- Modal Footer --}}
            <div
                style="display:flex; align-items:center; justify-content:flex-end; gap:8px;
                    padding:16px 24px; border-top:1px solid #e5e7eb;">
                <button type="button" onclick="tutupModal()"
                    style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                       font-size:12px; font-weight:600; color:#374151; border:1px solid #e5e7eb; cursor:pointer;
                       border-radius:8px; background:#f1f5f9; transition:background 0.15s;"
                    onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                    Tutup
                </button>
                <button type="button" onclick="simpanCredential()"
                    style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px;
                       font-size:12px; font-weight:700; color:#fff; border:none; cursor:pointer;
                       border-radius:8px; background:var(--accent); box-shadow:0 2px 8px var(--accent-shadow);
                       transition:background 0.15s;"
                    onmouseover="this.style.background='var(--accent-strong)'" onmouseout="this.style.background='var(--accent)'">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
@endpush
