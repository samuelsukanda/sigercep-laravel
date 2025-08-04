<?php

namespace App\Http\Controllers;

use App\Models\KomplainIpsrs;
use App\Models\KomplainOutsourcingVendor;
use App\Models\KesehatanLingkungan;
use App\Models\ReservasiRuangan;
use App\Models\ReservasiKendaraan;
use App\Models\DesainGrafis;
use App\Models\KecelakaanKerja;
use App\Models\KesiapanAmbulance;
use App\Models\Mutu;
use App\Models\ManajemenRisiko;
use App\Models\Visitasi;
use App\Models\PeminjamanAset;
use App\Models\PemindahanAset;
use App\Models\PengembalianAset;
use App\Models\LaporanAsetRusak;
use App\Models\Peminjaman;
use App\Models\Toner;
use App\Models\BankSpo;
use App\Models\Utw;
use App\Models\PeraturanPerusahaan;
use App\Models\MandatoryTraining;


class DashboardController extends Controller
{

    public function index()
    {
        // Komplain IPSRS
        $totalKomplainIpsrs = KomplainIpsrs::count();
        $latestIpsrs = KomplainIpsrs::latest('created_at')->first();
        $lastInputTimeIpsrs = $latestIpsrs ? $latestIpsrs->created_at->diffForHumans() : 'Belum ada data';

        // Komplain Outsourcing Vendor
        $totalKomplainOutsourcingVendor = KomplainOutsourcingVendor::count();
        $latestVendor = KomplainOutsourcingVendor::latest('created_at')->first();
        $lastInputTimeVendor = $latestVendor ? $latestVendor->created_at->diffForHumans() : 'Belum ada data';

        // Kesehatan Lingkungan
        $totalKesehatanLingkungan = KesehatanLingkungan::count();
        $latestKesehatanLingkungan = KesehatanLingkungan::latest('created_at')->first();
        $lastInputTimeKesehatanLingkungan = $latestKesehatanLingkungan ? $latestKesehatanLingkungan->created_at->diffForHumans() : 'Belum ada data';

        // Reservasi Ruangan
        $totalReservasiRuangan = ReservasiRuangan::count();
        $latestRuangan = ReservasiRuangan::latest('created_at')->first();
        $lastInputTimeRuangan = $latestRuangan ? $latestRuangan->created_at->diffForHumans() : 'Belum ada data';

        // Reservasi Kendaraan
        $totalReservasiKendaraan = ReservasiKendaraan::count();
        $latestKendaraan = ReservasiKendaraan::latest('created_at')->first();
        $lastInputTimeKendaraan = $latestKendaraan ? $latestKendaraan->created_at->diffForHumans() : 'Belum ada data';

        // Desain Grafis
        $totalDesainGrafis = DesainGrafis::count();
        $latestDesain = DesainGrafis::latest('created_at')->first();
        $lastInputTimeDesain = $latestDesain ? $latestDesain->created_at->diffForHumans() : 'Belum ada data';

        // K3RS
        $totalK3RS = KecelakaanKerja::count();
        $latestK3RS = KecelakaanKerja::latest('created_at')->first();
        $lastInputTimeK3RS = $latestK3RS ? $latestK3RS->created_at->diffForHumans() : 'Belum ada data';

        // Mutu
        $totalMutu = Mutu::count();
        $latestMutu = Mutu::latest('created_at')->first();
        $lastInputTimeMutu = $latestMutu ? $latestMutu->created_at->diffForHumans() : 'Belum ada data';

        // Bank SPO
        $totalBankSpo = BankSpo::count();
        $latestBankSpo = BankSpo::latest('created_at')->first();
        $lastInputTimeBankSpo = $latestBankSpo ? $latestBankSpo->created_at->diffForHumans() : 'Belum ada data';

        // Manajemen Risiko
        $totalManajemenRisiko = ManajemenRisiko::count();
        $latestManajemenRisiko = ManajemenRisiko::latest('created_at')->first();
        $lastInputTimeManajemenRisiko = $latestManajemenRisiko ? $latestManajemenRisiko->created_at->diffForHumans() : 'Belum ada data';

        // UTW
        $totalUtw = Utw::count();
        $latestUtw = Utw::latest('created_at')->first();
        $lastInputTimeUtw = $latestUtw ? $latestUtw->created_at->diffForHumans() : 'Belum ada data';

        // PeraturanPerusahaan
        $totalPeraturanPerusahaan = PeraturanPerusahaan::count();
        $latestPeraturanPerusahaan = PeraturanPerusahaan::latest('created_at')->first();
        $lastInputTimePeraturanPerusahaan = $latestPeraturanPerusahaan ? $latestPeraturanPerusahaan->created_at->diffForHumans() : 'Belum ada data';

        // Mandatory Training
        $totalMandatoryTraining = MandatoryTraining::count();
        $latestMandatoryTraining = MandatoryTraining::latest('created_at')->first();
        $lastInputTimeMandatoryTraining = $latestMandatoryTraining ? $latestMandatoryTraining->created_at->diffForHumans() : 'Belum ada data';

        // Visitasi
        $totalVisitasi = Visitasi::count();
        $latestVisitasi = Visitasi::latest('created_at')->first();
        $lastInputTimeVisitasi = $latestVisitasi ? $latestVisitasi->created_at->diffForHumans() : 'Belum ada data';

        // Peminjaman Aset
        $totalPeminjamanAset = PeminjamanAset::count();
        $latestPeminjamanAset = PeminjamanAset::latest('created_at')->first();
        $lastInputTimePeminjamanAset = $latestPeminjamanAset ? $latestPeminjamanAset->created_at->diffForHumans() : 'Belum ada data';

        // Pemindahan Aset
        $totalPemindahanAset = PemindahanAset::count();
        $latestPemindahanAset = PemindahanAset::latest('created_at')->first();
        $lastInputTimePemindahanAset = $latestPemindahanAset ? $latestPemindahanAset->created_at->diffForHumans() : 'Belum ada data';

        // Pengembalian Aset
        $totalPengembalianAset = PengembalianAset::count();
        $latestPengembalianAset = PengembalianAset::latest('created_at')->first();
        $lastInputTimePengembalianAset = $latestPengembalianAset ? $latestPengembalianAset->created_at->diffForHumans() : 'Belum ada data';

        // Laporan Aset Rusak
        $totalLaporanAsetRusak = LaporanAsetRusak::count();
        $latestLaporanAsetRusak = LaporanAsetRusak::latest('created_at')->first();
        $lastInputTimeLaporanAsetRusak = $latestLaporanAsetRusak ? $latestLaporanAsetRusak->created_at->diffForHumans() : 'Belum ada data';

        // Peminjaman
        $totalPeminjaman = Peminjaman::count();
        $latestPeminjaman = Peminjaman::latest('created_at')->first();
        $lastInputTimePeminjaman = $latestPeminjaman ? $latestPeminjaman->created_at->diffForHumans() : 'Belum ada data';

        // Kesiapan Ambulance
        $totalAmbulance = KesiapanAmbulance::count();
        $latestAmbulance = KesiapanAmbulance::latest('created_at')->first();
        $lastInputTimeAmbulance = $latestAmbulance ? $latestAmbulance->created_at->diffForHumans() : 'Belum ada data';

        // Toner
        $totalToner = Toner::count();
        $latestToner = Toner::latest('created_at')->first();
        $lastInputTimeToner = $latestToner ? $latestToner->created_at->diffForHumans() : 'Belum ada data';

        return view('pages.dashboard', compact(
            'totalKomplainIpsrs',
            'lastInputTimeIpsrs',
            'totalKomplainOutsourcingVendor',
            'lastInputTimeVendor',
            'totalKesehatanLingkungan',
            'lastInputTimeKesehatanLingkungan',
            'totalReservasiRuangan',
            'lastInputTimeRuangan',
            'totalReservasiKendaraan',
            'lastInputTimeKendaraan',
            'totalDesainGrafis',
            'lastInputTimeDesain',
            'totalK3RS',
            'lastInputTimeK3RS',
            'totalMutu',
            'lastInputTimeMutu',
            'totalBankSpo',
            'lastInputTimeBankSpo',
            'totalManajemenRisiko',
            'lastInputTimeManajemenRisiko',
            'totalUtw',
            'lastInputTimeUtw',
            'totalPeraturanPerusahaan',
            'lastInputTimePeraturanPerusahaan',
            'totalMandatoryTraining',
            'lastInputTimeMandatoryTraining',
            'totalVisitasi',
            'lastInputTimeVisitasi',
            'totalPeminjamanAset',
            'lastInputTimePeminjamanAset',
            'totalPemindahanAset',
            'lastInputTimePemindahanAset',
            'totalPengembalianAset',
            'lastInputTimePengembalianAset',
            'totalLaporanAsetRusak',
            'lastInputTimeLaporanAsetRusak',
            'totalPeminjaman',
            'lastInputTimePeminjaman',
            'totalAmbulance',
            'lastInputTimeAmbulance',
            'totalToner',
            'lastInputTimeToner'
        ));
    }
}
