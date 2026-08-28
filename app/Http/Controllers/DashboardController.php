<?php

namespace App\Http\Controllers;

use App\Helpers\PermissionHelper;
use App\Models\BankIlmu;
use App\Models\BankSpo;
use App\Models\ChangeRequest;
use App\Models\DesainGrafis;
use App\Models\DokumenIt;
use App\Models\Hardware;
use App\Models\KecelakaanKerja;
use App\Models\KesehatanLingkungan;
use App\Models\KesiapanAmbulance;
use App\Models\KomiteMedik;
use App\Models\KomplainIpsrs;
use App\Models\KomplainOutsourcingVendor;
use App\Models\LaporanAsetRusak;
use App\Models\LaporanPerilaku;
use App\Models\MandatoryTraining;
use App\Models\ManajemenRisiko;
use App\Models\Mutu;
use App\Models\PelaporanIkp;
use App\Models\PemindahanAset;
use App\Models\Peminjaman;
use App\Models\PeminjamanAset;
use App\Models\PengajuanDokumen;
use App\Models\PengembalianAset;
use App\Models\PeraturanPerusahaan;
use App\Models\ReservasiKendaraan;
use App\Models\ReservasiRuangan;
use App\Models\SuratKeputusan;
use App\Models\Ticket;
use App\Models\Toner;
use App\Models\Utw;
use App\Models\Visitasi;

class DashboardController extends Controller
{
    public function index()
    {
        $can = fn ($menu, $action) => PermissionHelper::canAccess($menu, $action);

        $groups = $this->unitGroups();

        // Rekap per unit + data grafik
        $units = [];
        $chartLabels = [];
        for ($i = 0; $i < 6; $i++) {
            $chartLabels[] = now()->subMonths(5 - $i)->translatedFormat('M Y');
        }

        $palette = ['#7664E4', '#059669', '#d97706', '#0ea5e9', '#dc2626', '#0891b2', '#7c3aed', '#16a34a', '#e11d48', '#6366f1'];
        $chartUnits = [];
        $colorIdx = 0;

        foreach ($groups as $group) {
            $modules = [];

            foreach ($group['modules'] as [$menu, $model, $label, $icon]) {
                if (!$can($menu, 'read')) {
                    continue;
                }

                $stats = $this->scopedQuery($menu, $model)
                    ->selectRaw('COUNT(*) as c, MAX(created_at) as latest')
                    ->first();
                $count = (int) $stats->c;
                $last = $stats->latest ? \Carbon\Carbon::parse($stats->latest)->diffForHumans() : 'Belum ada data';

                $modules[] = [
                    'label' => $label,
                    'icon' => $icon,
                    'count' => $count,
                    'last' => $last,
                    'route' => $this->moduleRoute($menu),
                ];
            }

            if ($modules) {
                $units[] = [
                    'name' => $group['name'],
                    'icon' => $group['icon'],
                    'modules' => $modules,
                ];

                $monthly = array_fill(0, 6, 0);
                foreach ($group['modules'] as [$menu, $model]) {
                    if (!$can($menu, 'read')) {
                        continue;
                    }
                    $monthStart = now()->subMonths(5)->startOfMonth();
                    $rows = $this->scopedQuery($menu, $model)
                        ->where('created_at', '>=', $monthStart)
                        ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as c")
                        ->groupBy('ym')
                        ->pluck('c', 'ym');

                    foreach ($rows as $ym => $c) {
                        for ($i = 0; $i < 6; $i++) {
                            if ($ym === now()->subMonths(5 - $i)->format('Y-m')) {
                                $monthly[$i] += (int) $c;
                            }
                        }
                    }
                }

                $chartUnits[] = [
                    'name' => $group['name'],
                    'color' => $palette[$colorIdx % count($palette)],
                    'data' => $monthly,
                ];
                $colorIdx++;
            }
        }

        // Aktivitas terbaru
        $recentTickets = $this->scopedQuery('helpdesk', Ticket::class)
            ->with('user')
            ->latest('created_at')
            ->limit(5)
            ->get();
        $recentKomplain = KomplainIpsrs::latest('created_at')->limit(5)->get();
        $recentReservasi = ReservasiRuangan::latest('created_at')->limit(5)->get();

        $canManageHelpdesk = $can('helpdesk', 'manage');
        $ticketShowRoute = fn ($id) => $canManageHelpdesk ? route('admin.helpdesk.show', $id) : route('helpdesk.show', $id);
        $ticketIndexRoute = $canManageHelpdesk ? route('admin.helpdesk.index') : route('helpdesk.index');

        return view('pages.dashboard', compact(
            'units',
            'chartLabels',
            'chartUnits',
            'recentTickets',
            'recentKomplain',
            'recentReservasi',
            'ticketShowRoute',
            'ticketIndexRoute'
        ));
    }

    private function scopedQuery(string $menu, string $model)
    {
        if ($menu === 'helpdesk' && !PermissionHelper::canAccess('helpdesk', 'manage')) {
            return $model::where('user_id', auth()->id());
        }

        return $model::query();
    }

    private function moduleRoute(string $menu): string
    {
        $routes = [
            'helpdesk' => PermissionHelper::canAccess('helpdesk', 'manage') ? 'admin.helpdesk.index' : 'helpdesk.index',
            'dokumen_it' => 'dokumen-it.index',
            'bank_ilmu' => 'bank-ilmu.index',
            'hardware' => 'hardware.index',
            'change_request' => 'change-request.index',
            'komplain_ipsrs' => 'komplain.ipsrs.index',
            'outsourcing_vendor' => 'komplain.outsourcing-vendor.index',
            'kesehatan_lingkungan' => 'komplain.kesehatan-lingkungan.index',
            'reservasi_ruangan' => 'reservasi.ruangan.index',
            'reservasi_kendaraan' => 'reservasi.kendaraan.index',
            'desain_grafis' => 'desain-grafis.index',
            'kecelakaan_kerja' => 'kecelakaan-kerja.index',
            'kesiapan_ambulance' => 'kesiapan-ambulance.index',
            'mutu' => 'komite-mutu.mutu.index',
            'bank_spo' => 'komite-mutu.bank-spo.index',
            'manajemen_risiko' => 'komite-mutu.manajemen-risiko.index',
            'pelaporan_ikp' => 'komite-mutu.pelaporan-ikp.index',
            'pengajuan_dokumen' => 'komite-mutu.pengajuan-dokumen.index',
            'laporan_perilaku' => 'komite-mutu.laporan-perilaku.index',
            'utw' => 'sdm-hukum.utw.index',
            'peraturan_perusahaan' => 'sdm-hukum.peraturan-perusahaan.index',
            'surat_keputusan' => 'sdm-hukum.surat-keputusan.index',
            'mandatory_training' => 'sdm-hukum.mandatory-training.index',
            'peminjaman_aset' => 'pengadaan-aset.peminjaman-aset.index',
            'pengembalian_aset' => 'pengadaan-aset.pengembalian-aset.index',
            'pemindahan_aset' => 'pengadaan-aset.pemindahan-aset.index',
            'laporan_aset_rusak' => 'pengadaan-aset.laporan-aset-rusak.index',
            'komite_medik' => 'komite-medik.index',
            'toner' => 'toner.index',
            'visitasi' => 'visitasi.index',
            'peminjaman' => 'peminjaman.index',
        ];

        return $routes[$menu] ?? '#';
    }

    private function unitGroups(): array
    {
        return [
            'helpdesk' => [
                'name' => 'IT',
                'icon' => 'fa-laptop-code',
                'modules' => [
                    ['helpdesk', Ticket::class, 'Helpdesk', 'fa-headset'],
                    ['dokumen_it', DokumenIt::class, 'Dokumen IT', 'fa-file-alt'],
                    ['hardware', Hardware::class, 'Ceklis Hardware', 'fa-server'],
                    ['change_request', ChangeRequest::class, 'Change Request', 'fa-code-branch'],
                ],
            ],
            'bank-ilmu' => [
                'name' => 'Bank Ilmu',
                'icon' => 'fa-book',
                'modules' => [
                    ['bank_ilmu', BankIlmu::class, 'Bank Ilmu', 'fa-book'],
                ],
            ],
            'komplain' => [
                'name' => 'Komplain',
                'icon' => 'fa-wrench',
                'modules' => [
                    ['komplain_ipsrs', KomplainIpsrs::class, 'Komplain IPSRS', 'fa-wrench'],
                    ['outsourcing_vendor', KomplainOutsourcingVendor::class, 'Outsourcing Vendor', 'fa-building'],
                    ['kesehatan_lingkungan', KesehatanLingkungan::class, 'Kesehatan Lingkungan', 'fa-leaf'],
                ],
            ],
            'reservasi' => [
                'name' => 'Reservasi',
                'icon' => 'fa-calendar-days',
                'modules' => [
                    ['reservasi_ruangan', ReservasiRuangan::class, 'Ruangan', 'fa-door-open'],
                    ['reservasi_kendaraan', ReservasiKendaraan::class, 'Kendaraan', 'fa-car'],
                ],
            ],
            'kreatif' => [
                'name' => 'Kreatif',
                'icon' => 'fa-palette',
                'modules' => [
                    ['desain_grafis', DesainGrafis::class, 'Desain Grafis', 'fa-palette'],
                ],
            ],
            'k3rs' => [
                'name' => 'K3RS',
                'icon' => 'fa-radiation',
                'modules' => [
                    ['kecelakaan_kerja', KecelakaanKerja::class, 'Kecelakaan Kerja', 'fa-user-shield'],
                ],
            ],
            'ambulance' => [
                'name' => 'Kesiapan Ambulance',
                'icon' => 'fa-notes-medical',
                'modules' => [
                    ['kesiapan_ambulance', KesiapanAmbulance::class, 'Kesiapan Ambulance', 'fa-notes-medical'],
                ],
            ],
            'mutu' => [
                'name' => 'Komite Mutu',
                'icon' => 'fa-clipboard-check',
                'modules' => [
                    ['mutu', Mutu::class, 'Mutu', 'fa-chart-line'],
                    ['bank_spo', BankSpo::class, 'Bank SPO', 'fa-book-open'],
                    ['manajemen_risiko', ManajemenRisiko::class, 'Manajemen Risiko', 'fa-shield-halved'],
                    ['pelaporan_ikp', PelaporanIkp::class, 'Pelaporan IKP', 'fa-file-medical'],
                    ['pengajuan_dokumen', PengajuanDokumen::class, 'Pengajuan Dokumen', 'fa-file-signature'],
                    ['laporan_perilaku', LaporanPerilaku::class, 'Laporan Perilaku', 'fa-user-check'],
                ],
            ],
            'sdm' => [
                'name' => 'SDM & Hukum',
                'icon' => 'fa-balance-scale',
                'modules' => [
                    ['utw', Utw::class, 'UTW', 'fa-balance-scale'],
                    ['peraturan_perusahaan', PeraturanPerusahaan::class, 'Peraturan Perusahaan', 'fa-file-contract'],
                    ['surat_keputusan', SuratKeputusan::class, 'Surat Keputusan', 'fa-file-invoice'],
                    ['mandatory_training', MandatoryTraining::class, 'Mandatory Training', 'fa-user-graduate'],
                ],
            ],
            'aset' => [
                'name' => 'Pengadaan Aset',
                'icon' => 'fa-warehouse',
                'modules' => [
                    ['peminjaman_aset', PeminjamanAset::class, 'Peminjaman Aset', 'fa-truck-ramp-box'],
                    ['pengembalian_aset', PengembalianAset::class, 'Pengembalian Aset', 'fa-rotate-left'],
                    ['pemindahan_aset', PemindahanAset::class, 'Pemindahan Aset', 'fa-right-left'],
                    ['laporan_aset_rusak', LaporanAsetRusak::class, 'Laporan Aset Rusak', 'fa-triangle-exclamation'],
                ],
            ],
            'komite-medik' => [
                'name' => 'Komite Medik',
                'icon' => 'fa-laptop-medical',
                'modules' => [
                    ['komite_medik', KomiteMedik::class, 'Komite Medik', 'fa-laptop-medical'],
                ],
            ],
            'lainnya' => [
                'name' => 'Lainnya',
                'icon' => 'fa-boxes-stacked',
                'modules' => [
                    ['toner', Toner::class, 'Toner', 'fa-print'],
                    ['visitasi', Visitasi::class, 'Visitasi', 'fa-paper-plane'],
                    ['peminjaman', Peminjaman::class, 'Peminjaman Barang', 'fa-hand-holding'],
                ],
            ],
        ];
    }
}