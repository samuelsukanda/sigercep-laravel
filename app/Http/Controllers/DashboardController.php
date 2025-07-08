<?php

namespace App\Http\Controllers;

use App\Models\KomplainIpsrs;
use App\Models\KomplainOutsourcingVendor;
use App\Models\ReservasiRuangan;
use App\Models\ReservasiKendaraan;
use App\Models\DesainGrafis;
use App\Models\KecelakaanKerja;
use App\Models\Mutu;
use App\Models\ManajemenRisiko;
use App\Models\Visitasi;

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

        // Manajemen Risiko
        $totalManajemenRisiko = ManajemenRisiko::count();
        $latestManajemenRisiko = ManajemenRisiko::latest('created_at')->first();
        $lastInputTimeManajemenRisiko = $latestManajemenRisiko ? $latestManajemenRisiko->created_at->diffForHumans() : 'Belum ada data';

        // Visitasi
        $totalVisitasi = Visitasi::count();
        $latestVisitasi = Visitasi::latest('created_at')->first();
        $lastInputTimeVisitasi = $latestVisitasi ? $latestVisitasi->created_at->diffForHumans() : 'Belum ada data';

        return view('pages.dashboard', compact(
            'totalKomplainIpsrs',
            'lastInputTimeIpsrs',
            'totalKomplainOutsourcingVendor',
            'lastInputTimeVendor',
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
            'totalManajemenRisiko',
            'lastInputTimeManajemenRisiko',
            'totalVisitasi',
            'lastInputTimeVisitasi',
        ));
    }
}
