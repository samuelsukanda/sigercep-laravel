@extends('layouts.app')

@section('title', 'SIGERCEP - Evaluasi Hardware')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full max-w-full px-3 mx-auto mt-0">

                {{-- Header --}}
                <x-page-header icon="fa-chart-simple" title="Evaluasi Hardware" subtitle="Evaluasi kinerja hardware" />

                {{-- Filter Tahun & Aksi --}}
                <div
                    style="background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08);
                            border:1px solid #e5e7eb; padding:16px 20px; margin-bottom:20px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
                        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                            <label style="font-size:12px; font-weight:600; color:#4b5563;">Filter Tahun:</label>
                            <select id="filterTahun" onchange="gantiFilterEvaluasi()"
                                style="padding:8px 12px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; outline:none; min-width:120px;">
                                <option value="">Semua Tahun</option>
                            </select>
                            
                            <label style="font-size:12px; font-weight:600; color:#4b5563; margin-left:8px;">Bulan:</label>
                            <select id="filterBulan" onchange="gantiFilterEvaluasi()"
                                style="padding:8px 12px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; outline:none; min-width:120px;">
                                <option value="">Semua Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div>
                            <button type="button" onclick="bukaModalEvaluasi()"
                                style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                                       font-size:12px; font-weight:600; color:#fff; border:none; cursor:pointer;
                                       border-radius:8px; background:var(--accent); box-shadow:0 2px 6px var(--accent-shadow);">
                                TAMBAH DATA
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Konten Evaluasi --}}
                <div id="kontenEvaluasi">
                    {{-- Diisi oleh JS --}}
                </div>

                {{-- Pagination --}}
                <div id="paginationNav" style="display:none;"></div>

                {{-- Empty State --}}
                <div id="emptyState" style="display:none; text-align:center; padding:60px 20px;">
                    <div
                        style="width:72px; height:72px; border-radius:50%; background:#f3f4f6;
                                display:flex; align-items:center; justify-content:center; margin:0 auto 16px;">
                        <i class="fas fa-inbox" style="font-size:28px; color:#d1d5db;"></i>
                    </div>
                    <h5 style="color:#374151; font-weight:600; margin-bottom:8px;">Belum Ada Data Evaluasi</h5>
                    <p style="color:#9ca3af; font-size:13px;">Data evaluasi akan muncul di sini setelah Anda menyimpan
                        evaluasi dari halaman Hardware.</p>
                    <a href="{{ route('hardware.index') }}"
                        style="display:inline-flex; align-items:center; gap:8px; margin-top:16px; padding:10px 20px;
                               font-size:13px; font-weight:600; color:#fff; border:none; cursor:pointer;
                               border-radius:8px; background:var(--accent); text-decoration:none;">
                        <i class="fas fa-arrow-left"></i> Ke Halaman Hardware
                    </a>
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
    </style>
    <script>
        var semuaDataGlobal = [];
        var halamanSekarang = 1;
        var limitPerHalaman = 10;

        document.addEventListener('DOMContentLoaded', function() {
            muatSemuaEvaluasi();
        });

        function muatSemuaEvaluasi() {
            var konten = document.getElementById('kontenEvaluasi');
            konten.innerHTML =
                '<div style="text-align:center;padding:40px;color:#9ca3af;"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i><p style="margin-top:12px;font-size:13px;">Memuat data evaluasi...</p></div>';

            fetch('{{ route('hardware.evaluasi.semua') }}', {
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
                    inisialisasiFilterTahun();
                    renderSemuaEvaluasi();
                })
                .catch(function() {
                    konten.innerHTML =
                        '<div style="text-align:center;padding:40px;color:#ef4444;font-size:13px;"><i class="fas fa-exclamation-triangle"></i> Gagal memuat data.</div>';
                });
        }

        function namaBulan(yearMonth) {
            var parts = yearMonth.split('-');
            var date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
            return date.toLocaleString('id-ID', {
                month: 'long',
                year: 'numeric'
            });
        }

        function inisialisasiFilterTahun() {
            var tahunSet = {};
            semuaDataGlobal.forEach(function(item) {
                var tahun = item.bulan.split('-')[0];
                tahunSet[tahun] = true;
            });

            var select = document.getElementById('filterTahun');
            select.innerHTML = '<option value="">Semua Tahun</option>';
            Object.keys(tahunSet).sort(function(a, b) {
                return b - a;
            }).forEach(function(tahun) {
                var opt = document.createElement('option');
                opt.value = tahun;
                opt.textContent = tahun;
                select.appendChild(opt);
            });
        }

        function renderSemuaEvaluasi() {
            var semua = semuaDataGlobal;
            var tahunFilter = document.getElementById('filterTahun').value;
            var bulanFilter = document.getElementById('filterBulan').value;

            if (tahunFilter) {
                semua = semua.filter(function(item) {
                    return item.bulan.startsWith(tahunFilter);
                });
            }

            if (bulanFilter) {
                semua = semua.filter(function(item) {
                    return item.bulan.endsWith('-' + bulanFilter);
                });
            }

            var konten = document.getElementById('kontenEvaluasi');
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
                html += cardEvaluasiHtml(item);
            });

            konten.innerHTML = html;
            renderPaginationEvaluasi(semua.length, totalHalaman);
        }

        function cardEvaluasiHtml(item) {
            var namaB = namaBulan(item.bulan);
            var jumlahKendala = item.rows.length;

            var barisHtml = '';
            item.rows.forEach(function(row) {
                barisHtml += `
                <tr style="border-bottom:1px solid #f3f4f6;">
                    <td style="padding:12px 16px; text-align:center; font-weight:600; color:#6b7280; font-size:13px; width:52px;">${row.nomor}</td>
                    <td style="padding:12px 16px; font-size:13px; color:#374151; white-space:pre-wrap;">${escapeHtml(row.kendala || '-')}</td>
                    <td style="padding:12px 16px; font-size:13px; color:#374151; white-space:pre-wrap;">${escapeHtml(row.rtl || '-')}</td>
                </tr>`;
            });

            return `
            <div style="background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,0.08);
                        border:1px solid #e5e7eb; margin-bottom:16px; overflow:hidden;">
                <div onclick="toggleKartu(this)" style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;
                            padding:14px 20px; background:linear-gradient(135deg,var(--accent-grad-1) 0%,var(--accent-grad-3) 100%);">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <i class="fas fa-chevron-down kartu-chevron" style="color:#fff; font-size:12px; transition:transform 0.2s;"></i>
                        <div style="width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.2);
                                    display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-calendar-alt" style="color:#fff; font-size:13px;"></i>
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:700; color:#fff;">${namaB}</div>
                            <div style="font-size:11px; color:rgba(255,255,255,0.75);">${jumlahKendala} kendala tercatat</div>
                        </div>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <button onclick="event.stopPropagation(); editBulan('${item.bulan}')"
                            class="btn-edit-row"
                            style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                   font-size:11px; font-weight:600; color:#3b82f6 !important; border:none; cursor:pointer;
                                   border-radius:6px; background:#fff;
                                   transition:background 0.18s, color 0.18s;"
                            onmouseover="this.style.setProperty('background','#eff6ff'); this.style.setProperty('color','#2563eb','important');"
                            onmouseout="this.style.setProperty('background','#fff'); this.style.setProperty('color','#3b82f6','important');">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button onclick="event.stopPropagation(); hapusBulan('${item.bulan}')"
                            class="btn-hapus-row"
                            style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px;
                                   font-size:11px; font-weight:600; color:#ef4444; border:none; cursor:pointer;
                                   border-radius:6px; background:#fff;
                                   transition:background 0.18s, color 0.18s;"
                            onmouseover="this.style.background='#fef2f2'; this.style.color='#dc2626';"
                            onmouseout="this.style.background='#fff'; this.style.color='#ef4444';">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </div>
                </div>
                <div style="display:none;">
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#f9fafb;">
                                    <th style="padding:10px 16px; text-align:center; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; width:52px;">No</th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">Kendala</th>
                                    <th style="padding:10px 16px; text-align:left; font-size:11px; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em;">RTL (Rencana Tindak Lanjut)</th>
                                </tr>
                            </thead>
                            <tbody>${barisHtml}</tbody>
                        </table>
                    </div>
                </div>
            </div>`;
        }

        function gantiFilterEvaluasi() {
            halamanSekarang = 1;
            renderSemuaEvaluasi();
        }

        function pindahHalamanEvaluasi(hal) {
            if (hal < 1) return;
            halamanSekarang = hal;
            renderSemuaEvaluasi();
        }

        function renderPaginationEvaluasi(total, totalHalaman) {
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
                var style = 'display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;' +
                    'padding:0 10px;font-size:12px;font-weight:600;border-radius:8px;border:1px solid #e5e7eb;' +
                    'background:#fff;color:#374151;cursor:pointer;';
                if (disabled) style += 'opacity:0.4;cursor:not-allowed;';
                if (active) style += 'background:var(--accent);border-color:var(--accent);color:#fff;';
                var onclick = disabled ? '' : ' onclick="pindahHalamanEvaluasi(' + hal + ')"';
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
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function editBulan(bulan) {
            document.getElementById('bulanEvaluasi').value = bulan;
            bukaModalEvaluasi();
        }

        function hapusBulan(bulan) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                customClass: {
                    confirmButton: 'btn-swal-confirm',
                    cancelButton: 'btn-swal-cancel'
                },
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ url('hardware/evaluasi') }}/' + bulan, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(json => {
                            if (json.success) {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Data evaluasi berhasil dihapus.',
                                    icon: 'success',
                                    customClass: {
                                        confirmButton: 'btn-swal-success'
                                    }
                                });
                                muatSemuaEvaluasi();
                            }
                        });
                }
            });
        }

        function cetakSemuaEvaluasi() {
            var semua = semuaDataGlobal;
            var tahunFilter = document.getElementById('filterTahun').value;
            if (tahunFilter) {
                semua = semua.filter(function(i) {
                    return i.bulan.startsWith(tahunFilter);
                });
            }
            if (semua.length === 0) {
                alert('Tidak ada data evaluasi untuk dicetak.');
                return;
            }

            var semuaRows = '';
            semua.forEach(function(item) {
                var namaB = namaBulan(item.bulan);
                semuaRows +=
                    `<tr><td colspan="3" style="padding:12px 10px 6px; font-weight:700; font-size:14px; color:var(--accent); border:none; border-top:2px solid var(--accent);">${namaB}</td></tr>`;
                item.rows.forEach(function(row) {
                    semuaRows += `<tr>
                <td style="border:1px solid #ddd;padding:10px;text-align:center;font-weight:600;color:#555;">${row.nomor}</td>
                <td style="border:1px solid #ddd;padding:10px;">${row.kendala || '-'}</td>
                <td style="border:1px solid #ddd;padding:10px;">${row.rtl || '-'}</td>
            </tr>`;
                });
            });

            var judul = tahunFilter ? 'Evaluasi Hardware Tahun ' + tahunFilter : 'Evaluasi Hardware — Semua Bulan';
            bukaJendelaCetak(judul, judul, semuaRows);
        }

        function bukaJendelaCetak(title, subtitle, tableRows) {
            var win = window.open('', '_blank');
            win.document.write(`<!DOCTYPE html><html>
    <head>
        <meta charset="UTF-8">
        <title>${title}</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 30px; color: #333; }
            h2 { text-align: center; color: var(--accent); margin-bottom: 4px; }
            p { text-align: center; color: #666; font-size: 13px; margin-top: 0 }
            table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 13px; }
            th { background-color: var(--accent); color: white; padding: 10px; text-align: left; border: 1px solid var(--accent); }
            th:first-child { text-align: center; width: 50px; }
            @media print { body { padding: 15px; } }
        </style>
    </head>
    <body>
        <h2>Evaluasi Hardware</h2>
        <p>${subtitle}</p>
        <table>
            <thead><tr>
                <th>No</th><th>Kendala</th><th>RTL (Rencana Tindak Lanjut)</th>
            </tr></thead>
            <tbody>${tableRows}</tbody>
        </table>
        <script>window.onload = function(){ window.print(); }<\/script>
    </body></html>`);
            win.document.close();
        }

        // ===== EVALUASI MODAL =====
        function bukaModalEvaluasi() {
            var modal = document.getElementById('modalEvaluasi');
            modal.style.display = 'flex';
            updateJudulBulan();
            muatDataEvaluasi();
        }

        function tutupModalEvaluasi() {
            var modal = document.getElementById('modalEvaluasi');
            modal.style.display = 'none';
        }

        // Tutup modal saat klik backdrop
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('modalEvaluasi').addEventListener('click', function(e) {
                if (e.target === this) tutupModalEvaluasi();
            });
        });

        function updateJudulBulan() {
            const val = document.getElementById('bulanEvaluasi').value;
            if (!val) return;
            const [year, month] = val.split('-');
            const date = new Date(year, month - 1, 1);
            const bulanNama = date.toLocaleString('id-ID', {
                month: 'long',
                year: 'numeric'
            });
            document.getElementById('judulBulanEvaluasi').textContent = '— ' + bulanNama;
            // Load data bulan yang dipilih
            muatDataEvaluasi();
        }

        function tambahBarisEvaluasi(kendala, rtl) {
            const tbody = document.getElementById('tabelEvaluasiBody');
            const rowIndex = tbody.rows.length + 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td style="padding:10px 16px; text-align:center; font-weight:600; color:#6b7280;
                           border-bottom:1px solid #f3f4f6; font-size:13px;"
                    class="nomor-urut">${rowIndex}</td>
                <td style="padding:8px 16px; border-bottom:1px solid #f3f4f6;">
                    <textarea rows="2" placeholder="Masukkan kendala..."
                        style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #e5e7eb;
                               border-radius:8px; outline:none; resize:none; font-family:inherit;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e5e7eb'">${kendala || ''}</textarea>
                </td>
                <td style="padding:8px 16px; border-bottom:1px solid #f3f4f6;">
                    <textarea rows="2" placeholder="Masukkan rencana tindak lanjut..."
                        style="width:100%; padding:8px 12px; font-size:13px; border:1px solid #e5e7eb;
                               border-radius:8px; outline:none; resize:none; font-family:inherit;"
                        onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='#e5e7eb'">${rtl || ''}</textarea>
                </td>
                <td style="padding:8px 16px; text-align:center; border-bottom:1px solid #f3f4f6;">
                    <button type="button" onclick="hapusBarisEvaluasi(this)"
                        style="width:28px; height:28px; display:inline-flex; align-items:center; justify-content:center;
                               border:none; cursor:pointer; border-radius:8px; background:transparent; color:#f87171;"
                        onmouseover="this.style.background='#fef2f2'; this.style.color='#dc2626';"
                        onmouseout="this.style.background='transparent'; this.style.color='#f87171';">
                        <i class="fas fa-trash" style="font-size:11px;"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function muatDataEvaluasi() {
            const bulan = document.getElementById('bulanEvaluasi').value;
            const tbody = document.getElementById('tabelEvaluasiBody');
            tbody.innerHTML =
                '<tr><td colspan="4" style="text-align:center;padding:20px;color:#9ca3af;font-size:13px;"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>';

            fetch('{{ route('hardware.evaluasi.data') }}?bulan=' + bulan, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    tbody.innerHTML = '';
                    if (json.rows && json.rows.length > 0) {
                        json.rows.forEach(function(row) {
                            tambahBarisEvaluasi(row.kendala, row.rtl);
                        });
                    } else {
                        tambahBarisEvaluasi();
                    }
                })
                .catch(function() {
                    tbody.innerHTML = '';
                    tambahBarisEvaluasi();
                });
        }

        function hapusBarisEvaluasi(btn) {
            const tbody = document.getElementById('tabelEvaluasiBody');
            if (tbody.rows.length <= 1) return; // minimal 1 baris
            btn.closest('tr').remove();
            // Update nomor urut
            Array.from(tbody.rows).forEach((row, i) => {
                row.querySelector('.nomor-urut').textContent = i + 1;
            });
        }

        function saveEvaluasi() {
            const bulan = document.getElementById('bulanEvaluasi').value;
            if (!bulan) return;

            const tbody = document.getElementById('tabelEvaluasiBody');
            const rows = [];

            Array.from(tbody.rows).forEach(function(row) {
                const kendala = row.querySelectorAll('textarea')[0].value.trim();
                const rtl = row.querySelectorAll('textarea')[1].value.trim();
                if (kendala || rtl) {
                    rows.push({
                        kendala: kendala,
                        rtl: rtl
                    });
                }
            });

            const btn = document.querySelector('[onclick="saveEvaluasi()"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            btn.disabled = true;

            fetch('{{ route('hardware.evaluasi.simpan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        bulan: bulan,
                        rows: rows
                    })
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    if (json.success) {
                        btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
                        btn.style.background = '#22c55e';
                        muatSemuaEvaluasi(); // Refresh data utama
                    } else {
                        btn.innerHTML = '<i class="fas fa-times"></i> Gagal!';
                        btn.style.background = '#ef4444';
                    }
                    btn.disabled = false;
                    setTimeout(function() {
                        btn.innerHTML = originalHTML;
                        btn.style.background = 'var(--accent)';
                    }, 1800);
                })
                .catch(function() {
                    btn.innerHTML = '<i class="fas fa-times"></i> Error!';
                    btn.style.background = '#ef4444';
                    btn.disabled = false;
                    setTimeout(function() {
                        btn.innerHTML = originalHTML;
                        btn.style.background = 'var(--accent)';
                    }, 1800);
                });
        }
    </script>
@endpush

@push('modals')
    {{-- Modal Evaluasi --}}
    <div id="modalEvaluasi"
        style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:9999;
           background:rgba(0,0,0,0.5); align-items:center; justify-content:center; padding:16px;">
        <div
            style="background:#fff; border-radius:16px; box-shadow:0 25px 50px rgba(0,0,0,0.25);
                width:100%; max-width:860px; max-height:90vh; display:flex; flex-direction:column;
                margin:auto;">
            {{-- Modal Header --}}
            <div
                style="display:flex; align-items:center; justify-content:space-between; padding:20px 24px; border-bottom:1px solid #e5e7eb;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div
                        style="width:36px; height:36px; border-radius:8px; background:var(--accent);
                            display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-plus" style="color:#fff; font-size:14px;"></i>
                    </div>
                    <div>
                        <h5 style="margin:0; font-size:15px; font-weight:700; color:#1f2937;">Tambah Data Evaluasi</h5>
                        <p style="margin:0; font-size:11px; color:#6b7280;">Tambah/Ubah Evaluasi Permasalahan &amp; Rencana
                            Tindak Lanjut Per Bulan</p>
                    </div>
                </div>
                <button type="button" onclick="tutupModalEvaluasi()"
                    style="width:32px; height:32px; border:none; background:transparent; cursor:pointer;
                       border-radius:8px; display:flex; align-items:center; justify-content:center;
                       color:#9ca3af; font-size:14px;"
                    onmouseover="this.style.background='#f3f4f6'; this.style.color='#374151';"
                    onmouseout="this.style.background='transparent'; this.style.color='#9ca3af';">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Pilih Bulan --}}
            <div style="padding:12px 24px; border-bottom:1px solid #f3f4f6; background:#f9fafb;">
                <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
                    <label style="font-size:11px; font-weight:600; color:#4b5563;">Pilih Bulan:</label>
                    <input type="month" id="bulanEvaluasi"
                        style="padding:6px 12px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; outline:none;"
                        value="{{ now()->format('Y-m') }}" onchange="updateJudulBulan()">
                    <span id="judulBulanEvaluasi" style="font-size:13px; font-weight:600; color:var(--accent);"></span>
                </div>
            </div>

            {{-- Modal Body --}}
            <div style="flex:1; overflow-y:auto; padding:20px 24px;">
                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <thead>
                        <tr style="background-color:var(--accent);">
                            <th
                                style="color:#fff; text-align:center; font-weight:600; padding:12px 16px;
                                   border-radius:8px 0 0 0; width:56px;">
                                No</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:12px 16px;">Kendala</th>
                            <th style="color:#fff; text-align:left; font-weight:600; padding:12px 16px;">RTL (Rencana Tindak
                                Lanjut)</th>
                            <th
                                style="color:#fff; text-align:center; font-weight:600; padding:12px 16px;
                                   border-radius:0 8px 0 0; width:56px;">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelEvaluasiBody">
                        {{-- Baris diisi via JS --}}
                    </tbody>
                </table>

                {{-- Tombol Tambah Baris --}}
                <div style="margin-top:12px;">
                    <button type="button" onclick="tambahBarisEvaluasi()"
                        style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                           font-size:11px; font-weight:600; color:#fff; border:none; cursor:pointer;
                           border-radius:8px; background:var(--accent); box-shadow:0 1px 4px rgba(0,0,0,0.2);">
                        <i class="fas fa-plus"></i> Tambah Baris
                    </button>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div
                style="display:flex; align-items:center; justify-content:flex-end; gap:8px;
                    padding:16px 24px; border-top:1px solid #e5e7eb;">
                <button type="button" onclick="saveEvaluasi()"
                    style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                       font-size:11px; font-weight:600; color:#fff; border:none; cursor:pointer;
                       border-radius:8px; background:var(--accent); box-shadow:0 1px 4px rgba(0,0,0,0.2);">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <button type="button" onclick="tutupModalEvaluasi()"
                    style="display:inline-flex; align-items:center; gap:8px; padding:8px 16px;
                       font-size:11px; font-weight:600; color:#374151; border:none; cursor:pointer;
                       border-radius:8px; background:#e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,0.1);">
                    Tutup
                </button>
            </div>
        </div>
    </div>
@endpush
