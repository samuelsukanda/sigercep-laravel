<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarRisiko;
use Illuminate\Support\Facades\Validator;

class ManajemenRisikoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manajemen_risiko,read')->only(['index', 'show']);
        $this->middleware('permission:manajemen_risiko,create')->only(['create', 'store']);
        $this->middleware('permission:manajemen_risiko,update')->only(['edit', 'update']);
        $this->middleware('permission:manajemen_risiko,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $unitFilter = $request->get('unit');
        $tingkatFilter = $request->get('tingkat');
        $kodeFilter = $request->get('kode_risiko');

        // Distinct options for filters
        $unitOptions = DaftarRisiko::select('unit')->distinct()->orderBy('unit')->pluck('unit');
        $tingkatOptions = ['Sangat Rendah', 'Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'];
        $kodeOptions = DaftarRisiko::select('kode_risiko')->whereNotNull('kode_risiko')->distinct()->orderBy('kode_risiko')->pluck('kode_risiko');

        $query = DaftarRisiko::query();

        if ($unitFilter) {
            $query->where('unit', $unitFilter);
        }
        
        if ($tingkatFilter) {
            $query->where('analisis_tingkat', $tingkatFilter);
        }

        if ($kodeFilter) {
            $query->where('kode_risiko', $kodeFilter);
        }

        $risikos = $query->latest()->get();

        // Statistics
        $totalRisiko = DaftarRisiko::count();
        $totalTinggiSangatTinggi = DaftarRisiko::whereIn('analisis_tingkat', ['Tinggi', 'Sangat Tinggi'])->count();
        $jumlahUnit = DaftarRisiko::select('unit')->distinct()->count();

        $isFiltered = $request->hasAny(['unit', 'tingkat', 'kode_risiko']);

        return view('pages.komite-mutu.manajemen-risiko.index', compact(
            'risikos', 'unitFilter', 'tingkatFilter', 'kodeFilter', 
            'unitOptions', 'tingkatOptions', 'kodeOptions',
            'totalRisiko', 'totalTinggiSangatTinggi', 'jumlahUnit', 'isFiltered'
        ));
    }

    public function create()
    {
        return view('pages.komite-mutu.manajemen-risiko.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'risiko' => 'required|string',
            'kode_risiko' => 'nullable|string|max:255',
            'sebab' => 'nullable|string',
            'sumber_risiko' => 'nullable|string|max:255',
            'c_uc' => 'nullable|string|max:50',
            'dampak' => 'nullable|string',
            'pengendalian' => 'nullable|string',
            'analisis_p' => 'nullable|numeric',
            'analisis_d' => 'nullable|numeric',
            'analisis_bobot' => 'nullable|numeric',
            'analisis_nilai' => 'nullable|numeric',
            'analisis_tingkat' => 'nullable|string|max:255',
            'target_waktu' => 'nullable|string|max:255',
            'mitigasi_tw1_p' => 'nullable|numeric',
            'mitigasi_tw1_d' => 'nullable|numeric',
            'mitigasi_tw1_bobot' => 'nullable|numeric',
            'mitigasi_tw1_nilai' => 'nullable|numeric',
            'mitigasi_tw1_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw2_p' => 'nullable|numeric',
            'mitigasi_tw2_d' => 'nullable|numeric',
            'mitigasi_tw2_bobot' => 'nullable|numeric',
            'mitigasi_tw2_nilai' => 'nullable|numeric',
            'mitigasi_tw2_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw3_p' => 'nullable|numeric',
            'mitigasi_tw3_d' => 'nullable|numeric',
            'mitigasi_tw3_bobot' => 'nullable|numeric',
            'mitigasi_tw3_nilai' => 'nullable|numeric',
            'mitigasi_tw3_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw4_p' => 'nullable|numeric',
            'mitigasi_tw4_d' => 'nullable|numeric',
            'mitigasi_tw4_bobot' => 'nullable|numeric',
            'mitigasi_tw4_nilai' => 'nullable|numeric',
            'mitigasi_tw4_tingkat' => 'nullable|string|max:255',
        ]);

        $validated['efektif'] = $request->has('efektif');
        $validated['tidak_efektif'] = $request->has('tidak_efektif');

        DaftarRisiko::create($validated);

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $risiko = DaftarRisiko::findOrFail($id);
        return view('pages.komite-mutu.manajemen-risiko.detail', compact('risiko'));
    }

    public function edit(string $id)
    {
        $risiko = DaftarRisiko::findOrFail($id);
        return view('pages.komite-mutu.manajemen-risiko.edit', compact('risiko'));
    }

    public function update(Request $request, string $id)
    {
        $risiko = DaftarRisiko::findOrFail($id);

        $validated = $request->validate([
            'unit' => 'required|string|max:255',
            'risiko' => 'required|string',
            'kode_risiko' => 'nullable|string|max:255',
            'sebab' => 'nullable|string',
            'sumber_risiko' => 'nullable|string|max:255',
            'c_uc' => 'nullable|string|max:50',
            'dampak' => 'nullable|string',
            'pengendalian' => 'nullable|string',
            'analisis_p' => 'nullable|numeric',
            'analisis_d' => 'nullable|numeric',
            'analisis_bobot' => 'nullable|numeric',
            'analisis_nilai' => 'nullable|numeric',
            'analisis_tingkat' => 'nullable|string|max:255',
            'target_waktu' => 'nullable|string|max:255',
            'mitigasi_tw1_p' => 'nullable|numeric',
            'mitigasi_tw1_d' => 'nullable|numeric',
            'mitigasi_tw1_bobot' => 'nullable|numeric',
            'mitigasi_tw1_nilai' => 'nullable|numeric',
            'mitigasi_tw1_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw2_p' => 'nullable|numeric',
            'mitigasi_tw2_d' => 'nullable|numeric',
            'mitigasi_tw2_bobot' => 'nullable|numeric',
            'mitigasi_tw2_nilai' => 'nullable|numeric',
            'mitigasi_tw2_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw3_p' => 'nullable|numeric',
            'mitigasi_tw3_d' => 'nullable|numeric',
            'mitigasi_tw3_bobot' => 'nullable|numeric',
            'mitigasi_tw3_nilai' => 'nullable|numeric',
            'mitigasi_tw3_tingkat' => 'nullable|string|max:255',
            'mitigasi_tw4_p' => 'nullable|numeric',
            'mitigasi_tw4_d' => 'nullable|numeric',
            'mitigasi_tw4_bobot' => 'nullable|numeric',
            'mitigasi_tw4_nilai' => 'nullable|numeric',
            'mitigasi_tw4_tingkat' => 'nullable|string|max:255',
        ]);

        $validated['efektif'] = $request->has('efektif');
        $validated['tidak_efektif'] = $request->has('tidak_efektif');

        $risiko->update($validated);

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $risiko = DaftarRisiko::findOrFail($id);
        $risiko->delete();

        return redirect()->route('komite-mutu.manajemen-risiko.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
