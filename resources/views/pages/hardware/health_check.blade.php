@extends('layouts.app')

@section('title', 'SIGERCEP - Health Check Hardware')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- Header --}}
                <div class="flex items-center justify-between mb-4 w-full">
                    <h3>Health Check Hardware</h3>
                </div>

                {{-- Filter --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                    <div class="px-5 py-4">
                        <div class="flex flex-wrap gap-3 items-end justify-between">
                            <div class="flex flex-wrap gap-3 items-end">
                                {{-- Cari --}}
                                <div class="flex flex-col" style="min-width:180px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Cari</label>
                                    <input type="text" id="filterCari" placeholder="Nama PC / IP / Unit / Petugas"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                        oninput="renderSemua()">
                                </div>

                                {{-- Periode Dari --}}
                                <div class="flex flex-col" style="min-width:150px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Dari</label>
                                    <input type="text" id="filterDari"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                        placeholder="Pilih tanggal" onchange="renderSemua()">
                                </div>

                                {{-- Periode Sampai --}}
                                <div class="flex flex-col" style="min-width:150px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Periode Sampai</label>
                                    <input type="text" id="filterSampai"
                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                        placeholder="Pilih tanggal" onchange="renderSemua()">
                                </div>

                                {{-- Status --}}
                                <div class="flex flex-col" style="min-width:150px;">
                                    <label class="text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                                    <select id="filterStatus"
                                        class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all"
                                        onchange="renderSemua()">
                                        <option value=""></option>
                                        <option value="Healthy">Healthy</option>
                                        <option value="Warning">Warning</option>
                                        <option value="Critical">Critical</option>
                                    </select>
                                </div>

                                <span id="totalData" class="text-xs text-gray-400 mb-2 ml-1"></span>
                            </div>

                            <div class="flex items-end">
                                <button type="button" onclick="bukaModalTambah()"
                                    class="inline-flex items-center justify-center h-9 px-4 text-xs font-semibold text-white uppercase rounded-lg shadow-md hover:shadow-sm active:opacity-85 transition-all"
                                    style="background-color: #7664E4 !important;">
                                    <i class="fas fa-plus mr-1"></i> Tambah Data
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konten --}}
                <div id="kontenHealthCheck">
                    {{-- Diisi oleh JS --}}
                </div>

                {{-- Empty State --}}
                <div id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
                    <div
                        style="width:72px; height:72px; border-radius:50%; background:#f3f4f6;
                                display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <i class="fas fa-heartbeat" style="font-size:28px; color:#d1d5db;"></i>
                    </div>
                    <h5 style="color:#374151; font-weight:600; margin-bottom:8px;">Belum Ada Data Health Check</h5>
                    <p style="color:#9ca3af; font-size:13px;">Klik <b>Tambah Data</b> untuk mencatat kesehatan hardware
                        PC.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var semuaDataGlobal = [];
        var kategoriKomponen = @json($kategoriKomponen);
        var statusOptions = @json($statusOptions);
        var daftarPc = @json($daftarPc);
        var edittingId = null;
        var fpDari, fpSampai;

        $(document).ready(function() {
            bangunOpsiPc();
            initSelect2();
            initFlatpickr();
            muatSemuaData();
        });

        function initFlatpickr() {
            fpDari = flatpickr('#filterDari', {
                dateFormat: 'd-m-Y'
            });
            fpSampai = flatpickr('#filterSampai', {
                dateFormat: 'd-m-Y'
            });
        }

        function initSelect2() {
            $('#selectPc').select2({
                placeholder: 'Pilih PC',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#modalHealthCheck')
            });
            $('#filterStatus').select2({
                placeholder: 'Semua Status',
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: Infinity
            });
            initStatusSelect2();
        }

        function initStatusSelect2() {
            $('#tabelItemBody select[name="status"]').each(function() {
                if ($(this).hasClass('select2-hidden-accessible')) return;
                $(this).select2({
                    width: '100%',
                    minimumResultsForSearch: Infinity,
                    dropdownParent: $('#modalHealthCheck')
                });
            });
        }

        // ===== LOAD & RENDER =====
        function muatSemuaData() {
            var konten = document.getElementById('kontenHealthCheck');
            konten.innerHTML =
                '<div style="text-align:center;padding:40px;color:#9ca3af;"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i><p style="margin-top:12px;font-size:13px;">Memuat data health check...</p></div>';

            fetch('{{ route('hardware.health-check.data') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(json) {
                    semuaDataGlobal = json.data || [];
                    renderSemua();
                })
                .catch(function() {
                    konten.innerHTML =
                        '<div style="text-align:center;padding:40px;color:#ef4444;font-size:13px;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data.</div>';
                });
        }

        function isoDmy(str) {
            if (!str) return '';
            var parts = str.split('-');
            return parts[2] + '-' + parts[1] + '-' + parts[0];
        }

        function filterSemua() {
            var semua = semuaDataGlobal;
            var cari = (document.getElementById('filterCari').value || '').toLowerCase().trim();
            var dari = isoDmy(document.getElementById('filterDari').value);
            var sampai = isoDmy(document.getElementById('filterSampai').value);
            var status = document.getElementById('filterStatus').value;

            if (cari) {
                semua = semua.filter(function(item) {
                    return (item.nama_pc || '').toLowerCase().indexOf(cari) >= 0 ||
                        (item.ip || '').toLowerCase().indexOf(cari) >= 0 ||
                        (item.unit || '').toLowerCase().indexOf(cari) >= 0;
                });
            }
            if (dari) {
                semua = semua.filter(function(item) { return item.created_at >= dari; });
            }
            if (sampai) {
                semua = semua.filter(function(item) { return item.created_at <= sampai; });
            }
            if (status) {
                semua = semua.filter(function(item) { return item.overall === status; });
            }
            return semua;
        }

        function renderSemua() {
            var semua = filterSemua();
            var konten = document.getElementById('kontenHealthCheck');
            var empty = document.getElementById('emptyState');

            if (semua.length === 0) {
                konten.innerHTML = '';
                empty.style.display = 'block';
                document.getElementById('totalData').textContent = '';
                return;
            }

            empty.style.display = 'none';
            document.getElementById('totalData').textContent = semua.length + ' data';

            var html = '';
            semua.forEach(function(item) {
                html += cardHtml(item);
            });
            konten.innerHTML = html;
        }

        function badgeHtml(status) {
            var map = {
                'Healthy': ['#dcfce7', '#166534'],
                'Warning': ['#fef3c7', '#92400e'],
                'Critical': ['#fee2e2', '#991b1b'],
                'Unknown': ['#f3f4f6', '#4b5563']
            };
            var c = map[status] || map['Healthy'];
            return '<span style="display:inline-flex;align-items:center;gap:6px;padding:3px 12px;border-radius:999px;' +
                'font-size:11px;font-weight:700;background:' + c[0] + ';color:' + c[1] +
                ';"><span style="width:6px;height:6px;border-radius:50%;background:' + c[1] +
                ';"></span>' + status + '</span>';
        }

        function cardHtml(item) {
            var counts = item.counts;
            var parts = [];
            if (counts.critical > 0) parts.push('<span style="color:#991b1b;font-weight:700;">' + counts.critical + ' Critical</span>');
            if (counts.warning > 0) parts.push('<span style="color:#92400e;font-weight:700;">' + counts.warning + ' Warning</span>');
            if (counts.healthy > 0) parts.push('<span style="color:#166534;font-weight:700;">' + counts.healthy + ' Healthy</span>');
            var ringkasan = parts.join(' &middot; ');

            var barisHtml = '';
            item.items.forEach(function(row, i) {
                barisHtml += `
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:9px 16px; text-align:center; font-weight:600; color:#9ca3af; font-size:12px; width:44px;">${i + 1}</td>
                    <td style="padding:9px 16px; font-size:12px; color:#1f2937; font-weight:600;">${escapeHtml(row.component)}</td>
                    <td style="padding:9px 16px; font-size:12px; color:#374151; text-align:center; white-space:nowrap;">
                        ${row.value !== null && row.value !== '' ? escapeHtml(row.value) + '%' : '-'}
                    </td>
                    <td style="padding:9px 16px; text-align:center; white-space:nowrap;">${badgeHtml(row.status)}</td>
                    <td style="padding:9px 16px; font-size:12px; color:#6b7280;">${escapeHtml(row.notes || '-')}</td>
                </tr>`;
            });

            return `
            <div style="background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08);
                        border:1px solid #e5e7eb; margin-bottom:16px; overflow:hidden;">
                <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
                            padding:14px 20px; background:linear-gradient(135deg,#7664E4 0%,#9b8af0 100%);">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.2);
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-server" style="color:#fff; font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:700; color:#fff;">${escapeHtml(item.nama_pc)}</div>
                            <div style="font-size:11px; color:rgba(255,255,255,0.8);">
                                <i class="fas fa-network-wired" style="margin-right:4px;"></i>${escapeHtml(item.ip || '-')}
                                <span style="margin:0 5px;">|</span>
                                <i class="fas fa-building" style="margin-right:4px;"></i>${escapeHtml(item.unit || '-')}
                                <span style="margin:0 5px;">|</span>
                                <i class="fas fa-layer-group" style="margin-right:4px;"></i>${escapeHtml(item.lantai || '-')}
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                        <div style="text-align:right;">
                            <div style="font-size:11px; color:rgba(255,255,255,0.75);">
                                <i class="fas fa-calendar-check" style="margin-right:4px;"></i>Created At: <b style="color:#fff;">${escapeHtml(item.created_at_formatted)}</b>
                            </div>
                        </div>
                        <div style="display:flex; gap:8px;">
                            <button onclick="editHealthCheck(${item.id})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                       font-size:11px; font-weight:600; color:#3b82f6; border:none; cursor:pointer; border-radius:6px; background:#fff;">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="hapusHealthCheck(${item.id})"
                                style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                       font-size:11px; font-weight:600; color:#ef4444; border:none; cursor:pointer; border-radius:6px; background:#fff;">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
                <div style="padding:10px 20px 0; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; border-bottom:1px solid #f3f4f6;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        ${badgeHtml(item.overall)}
                        <span style="font-size:11px; color:#9ca3af; font-weight:600;">${item.items.length} komponen</span>
                    </div>
                    <div style="font-size:11px; color:#9ca3af;">${ringkasan}</div>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; width:44px;">No</th>
                                <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Component</th>
                                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Value</th>
                                <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Status</th>
                                <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Notes</th>
                            </tr>
                        </thead>
                        <tbody>${barisHtml}</tbody>
                    </table>
                </div>
            </div>`;
        }

        function escapeHtml(text) {
            return String(text == null ? '' : text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
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
            document.getElementById('infoJenis').textContent = opt.getAttribute('data-jenis') || '';
        }

        function bukaModalTambah() {
            edittingId = null;
            document.getElementById('modalJudul').textContent = 'Tambah Health Check';
            document.getElementById('modalSubJudul').textContent =
                'Catat status kesehatan seluruh komponen hardware untuk satu PC.';
            setPc('');
            renderRows({});
            document.getElementById('modalHealthCheck').style.display = 'flex';
        }

        function editHealthCheck(id) {
            edittingId = id;
            fetch('{{ route('hardware.health-check.index') }}' + '/items/' + id, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(json) {
                    var check = json.check;
                    document.getElementById('modalJudul').textContent = 'Edit Health Check';
                    document.getElementById('modalSubJudul').textContent =
                        'Perbarui status kesehatan komponen untuk ' + check.nama_pc + '.';

                    setPc(check.nama_pc);

                    var itemMap = {};
                    json.items.forEach(function(item) {
                        itemMap[item.category + '||' + item.component] = item;
                    });
                    renderRows(itemMap);
                    document.getElementById('modalHealthCheck').style.display = 'flex';
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data tidak dapat dimuat.',
                        zIndex: 20000,
                        customClass: { confirmButton: 'btn-swal-success' }
                    });
                });
        }

        function tutupModal() {
            document.getElementById('modalHealthCheck').style.display = 'none';
        }

        $(document).ready(function() {
            $('#modalHealthCheck').on('click', function(e) {
                if (e.target === this) tutupModal();
            });
            $('#modalBodyScroll').on('scroll', function() {
                $('#modalHealthCheck select').select2('close');
            });
        });

        function renderRows(itemMap) {
            var tbody = document.getElementById('tabelItemBody');
            tbody.innerHTML = '';
            var no = 0;

            Object.keys(kategoriKomponen).forEach(function(category) {
                var th = document.createElement('tr');
                th.style.background = '#f5f3ff';
                th.innerHTML = `
                    <td colspan="4" style="padding:8px 16px; border-bottom:1px solid #e5e7eb; border-top:2px solid #e5e7eb;">
                        <span style="font-size:11px; font-weight:800; color:#7664E4; text-transform:uppercase; letter-spacing:0.05em;">
                            <i class="fas fa-chevron-circle-right" style="margin-right:6px;"></i>${category}
                        </span>
                    </td>`;
                tbody.appendChild(th);

                kategoriKomponen[category].forEach(function(component) {
                    no++;
                    var data = (itemMap[category + '||' + component] || {});
                    var tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid #f3f4f6';
                    tr.innerHTML = `
                        <td style="padding:8px 16px; text-align:center; font-weight:600; color:#9ca3af; font-size:12px; width:36px;">${no}</td>
                        <td style="padding:8px 12px;">
                            <div style="font-size:12.5px; font-weight:600; color:#1f2937;">${escapeHtml(component)}</div>
                            <input type="hidden" name="category" value="${escapeHtml(category)}">
                            <input type="hidden" name="component" value="${escapeHtml(component)}">
                        </td>
                        <td style="padding:8px 8px; width:150px;">
                            <div style="position:relative;">
                                <input type="number" step="any" min="0" name="value"
                                    placeholder="0"
                                    class="w-full px-3 py-2 pr-8 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                    value="${escapeHtml(data.value || '')}">
                                <span style="position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:12px; font-weight:700; pointer-events:none;">%</span>
                            </div>
                        </td>
                        <td style="padding:8px 8px; width:140px;">
                            <select name="status" class="w-full border-gray-300 text-gray-700 outline-none">
                                ${statusOptions.map(function(s) {
                                    return '<option value="' + s + '"' + ((data.status || 'Healthy') === s ? ' selected' : '') + '>' + s + '</option>';
                                }).join('')}
                            </select>
                        </td>
                        <td style="padding:8px 8px; width:180px;">
                            <input type="text" name="notes" placeholder="Notes"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                                value="${escapeHtml(data.notes || '')}">
                        </td>`;
                    tbody.appendChild(tr);
                });
            });

            if (no === 0) {
                tbody.innerHTML =
                    '<tr><td colspan="5" style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;">Tidak ada komponen.</td></tr>';
            }
            initStatusSelect2();
        }

        function collectRows() {
            var rows = [];
            var trs = document.querySelectorAll('#tabelItemBody tr');
            trs.forEach(function(tr) {
                var category = tr.querySelector('input[name="category"]');
                if (!category) return;
                var component = tr.querySelector('input[name="component"]').value;
                var value = tr.querySelector('input[name="value"]').value.trim();
                var status = tr.querySelector('select[name="status"]').value;
                var notes = tr.querySelector('input[name="notes"]').value.trim();
                if (status === 'Healthy' && !value && !notes) return;
                rows.push({
                    category: category.value,
                    component: component,
                    value: value,
                    status: status,
                    notes: notes
                });
            });
            return rows;
        }

        function saveHealthCheck() {
            var namaPc = $('#selectPc').val();
            if (!namaPc) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan pilih PC terlebih dahulu.',
                    zIndex: 20000,
                    customClass: { confirmButton: 'btn-swal-success' }
                });
                return;
            }

            var rows = collectRows();
            var payload = {
                id: edittingId,
                nama_pc: namaPc,
                ip: document.getElementById('fieldIp').value,
                unit: document.getElementById('fieldUnit').value,
                lantai: document.getElementById('fieldLantai').value,
                rows: rows
            };

            var btn = document.querySelector('[onclick="saveHealthCheck()"]');
            var originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            fetch('{{ route('hardware.health-check.simpan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                .then(function(res) { return res.json(); })
                .then(function(json) {
                    if (json.success) {
                        btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
                        btn.style.background = '#22c55e';
                        tutupModal();
                        muatSemuaData();
                    } else {
                        btn.innerHTML = '<i class="fas fa-times"></i> Gagal!';
                        btn.style.background = '#ef4444';
                        btn.disabled = false;
                        setTimeout(function() {
                            btn.innerHTML = originalHTML;
                            btn.style.background = '#7664E4';
                        }, 1800);
                    }
                })
                .catch(function() {
                    btn.innerHTML = '<i class="fas fa-times"></i> Error!';
                    btn.style.background = '#ef4444';
                    btn.disabled = false;
                    setTimeout(function() {
                        btn.innerHTML = originalHTML;
                        btn.style.background = '#7664E4';
                    }, 1800);
                });
        }

        function hapusHealthCheck(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data health check ini?',
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
                    fetch('{{ route('hardware.health-check.index') }}' + '/' + id, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(res) { return res.json(); })
                        .then(function(json) {
                            if (json.success) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Data health check berhasil dihapus.',
                                    icon: 'success',
                                    zIndex: 20000,
                                    customClass: { confirmButton: 'btn-swal-success' }
                                });
                                muatSemuaData();
                            }
                        });
                }
            });
        }
    </script>

    <style>
        .btn-swal-confirm {
            background-color: #ef4444 !important;
            color: #ffffff !important;
        }
        .btn-swal-cancel {
            background-color: #6b7280 !important;
            color: #ffffff !important;
        }
        .btn-swal-success {
            background-color: #7664E4 !important;
            color: #ffffff !important;
        }
    </style>
@endpush

@push('modals')
    <div id="modalHealthCheck"
        style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:9999;
           background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
        <div
            style="background:#fff; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,0.25);
                width:100%; max-width:960px; max-height:92vh; display:flex; flex-direction:column;
                margin:auto;">
            {{-- Modal Header --}}
            <div
                style="display:flex; align-items:center; justify-content:space-between; padding:16px 24px; border-radius:16px 16px 0 0; background:#7664E4;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,0.18);
                            display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-heartbeat" style="color:#fff; font-size:14px;"></i>
                    </div>
                    <div>
                        <h5 id="modalJudul" style="margin:0; font-size:15px; font-weight:700; color:#fff; line-height:1.2;">Tambah Health Check</h5>
                        <p id="modalSubJudul" style="margin:0; font-size:11px; color:rgba(255,255,255,0.8); margin-top:2px;">Catat status kesehatan seluruh komponen hardware untuk satu PC.</p>
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

            {{-- Informasi PC & Petugas --}}
            <div style="padding:16px 24px; border-bottom:1px solid #f3f4f6; background:#f9fafb;">
                <div style="display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:14px;">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih PC <span style="color:#ef4444;">*</span></label>
                        <select id="selectPc"
                            class="select2 w-full border-gray-300 text-gray-700 outline-none transition-all"
                            onchange="onPcChange()"></select>
                        <p id="infoJenis" style="font-size:11px; color:#7664E4; font-weight:700; margin-top:4px;"></p>
                    </div>
                </div>
                <input type="hidden" id="fieldIp">
                <input type="hidden" id="fieldUnit">
                <input type="hidden" id="fieldLantai">
            </div>

            {{-- Modal Body --}}
            <div id="modalBodyScroll" style="flex:1; overflow-y:auto; padding:16px 24px;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background-color:#7664E4; position:sticky; top:0; z-index:5;">
                            <th style="color:#fff; text-align:center; font-weight:600; padding:10px 16px; border-radius:8px 0 0 0; width:36px;">No</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:10px 16px;">Component</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:10px 8px; width:150px;">Value (%)</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:10px 8px; width:140px;">Status</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:10px 8px; border-radius:0 8px 0 0; width:180px;">Notes</th>
                        </tr>
                    </thead>
                    <tbody id="tabelItemBody">
                        {{-- Baris diisi via JS --}}
                    </tbody>
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
                <button type="button" onclick="saveHealthCheck()"
                    style="display:inline-flex; align-items:center; gap:8px; padding:8px 20px;
                       font-size:12px; font-weight:700; color:#fff; border:none; cursor:pointer;
                       border-radius:8px; background:#7664E4; box-shadow:0 2px 8px rgba(118,100,228,0.35);
                       transition:background 0.15s;"
                    onmouseover="this.style.background='#6453d4'" onmouseout="this.style.background='#7664E4'">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
@endpush