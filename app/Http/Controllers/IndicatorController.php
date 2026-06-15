<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\IndicatorTarget;
use App\Models\IndicatorValue;

class IndicatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:mutu,read')->only(['index', 'show']);
        $this->middleware('permission:mutu,create')->only(['create', 'store']);
        $this->middleware('permission:mutu,update')->only(['edit', 'update']);
        $this->middleware('permission:mutu,delete')->only(['destroy']);
    }

    /**
     * Tampilan daftar indikator dengan filter tahun & jenis.
     */
    public function index(Request $request)
    {
        $tahun   = $request->get('tahun', date('Y'));
        $jenis   = $request->get('jenis', 'INM');
        $jenisOptions = ['INM', 'IMPRS', 'IMP-UNIT', 'IMP-VENDOR', 'IMP-KOMITE'];
        $tahunOptions = [2025, 2026, 2027];

        $indicators = Indicator::where('jenis_indikator', $jenis)
            ->with([
                'targets' => fn($q) => $q->where('tahun', $tahun),
                'values'  => fn($q) => $q->where('tahun', $tahun)->orderBy('bulan'),
            ])
            ->orderByRaw("CAST(no_urut AS UNSIGNED)")
            ->get();

        return view('pages.komite-mutu.indicators.index', compact(
            'indicators', 'tahun', 'jenis', 'jenisOptions', 'tahunOptions'
        ));
    }
}
