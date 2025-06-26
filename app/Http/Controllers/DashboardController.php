<?php

namespace App\Http\Controllers;

use App\Models\KomplainIpsrs;
use App\Models\KomplainOutsourcingVendor;
use App\Models\ReservasiRuangan;
use App\Models\ReservasiKendaraan;

class DashboardController extends Controller
{

    public function index()
    {
        $totalKomplainIpsrs = KomplainIpsrs::count();
        $latestIpsrs = KomplainIpsrs::latest('created_at')->first();
        $lastInputTimeIpsrs = $latestIpsrs ? $latestIpsrs->created_at->diffForHumans() : 'Belum ada data';

        $totalKomplainOutsourcingVendor = KomplainOutsourcingVendor::count();
        $latestVendor = KomplainOutsourcingVendor::latest('created_at')->first();
        $lastInputTimeVendor = $latestVendor ? $latestVendor->created_at->diffForHumans() : 'Belum ada data';

        $totalReservasiRuangan = ReservasiRuangan::count();
        $latestRuangan = ReservasiRuangan::latest('created_at')->first();
        $lastInputTimeRuangan = $latestRuangan ? $latestRuangan->created_at->diffForHumans() : 'Belum ada data';

        $totalReservasiKendaraan = ReservasiKendaraan::count();
        $latestKendaraan = ReservasiKendaraan::latest('created_at')->first();
        $lastInputTimeKendaraan = $latestKendaraan ? $latestKendaraan->created_at->diffForHumans() : 'Belum ada data';

        return view('pages.dashboard', compact(
            'totalKomplainIpsrs',
            'lastInputTimeIpsrs',
            'totalKomplainOutsourcingVendor',
            'lastInputTimeVendor',
            'totalReservasiRuangan',
            'lastInputTimeRuangan',
            'totalReservasiKendaraan',
            'lastInputTimeKendaraan',
        ));
    }
}
