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

    public function index(Request $request)
    {
        $tahun   = $request->get('tahun', date('Y'));
        $jenis   = $request->get('jenis', 'INM');
        $jenisOptions = ['INM', 'IMPRS', 'IMP-UNIT', 'IMP-VENDOR', 'IMP-KOMITE'];
        $tahunOptions = [2025, 2026, 2027];

        $indicators = Indicator::where('jenis_indikator', $jenis)
            ->whereHas('targets', fn($q) => $q->where('tahun', $tahun))
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

    public function store(Request $request)
    {
        $request->validate([
            'no_urut' => 'nullable|string|max:50',
            'pj' => 'nullable|string|max:255',
            'nama_indikator' => 'required|string|max:255',
            'jenis_indikator' => 'required|string|max:50',
            'unit_terkait' => 'nullable|string|max:255',
            'target_value' => 'nullable|numeric',
            'tahun' => 'required|integer',
        ]);

        $indicator = Indicator::create([
            'no_urut' => $request->no_urut,
            'pj' => $request->pj,
            'nama_indikator' => $request->nama_indikator,
            'jenis_indikator' => $request->jenis_indikator,
            'unit_terkait' => $request->unit_terkait,
        ]);

        if ($request->filled('target_value')) {
            IndicatorTarget::create([
                'indicator_id' => $indicator->id,
                'tahun' => $request->tahun,
                'target_value' => $request->target_value,
            ]);
        }

        return back()->with('success', 'Indikator berhasil ditambahkan.');
    }
}
