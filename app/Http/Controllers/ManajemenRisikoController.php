<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DaftarRisiko;
use App\Helpers\PermissionHelper;

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
        if ($request->ajax()) {

            $columns = [
                '',
                'id',
                'unit',
                'risiko',
                'kode_risiko',
                'sebab',
                'dampak',
                'pengendalian',
                'analisis_tingkat',
                'target_waktu',
                'mitigasi_tw1_tingkat',
                'mitigasi_tw2_tingkat',
                'mitigasi_tw3_tingkat',
                'mitigasi_tw4_tingkat',
            ];

            $query = DaftarRisiko::query();

            if ($request->filled('unit')) {
                $query->where('unit', $request->unit);
            }

            if ($request->filled('tingkat')) {
                $query->where('analisis_tingkat', $request->tingkat);
            }

            if ($request->filled('kode_risiko')) {
                $query->where('kode_risiko', $request->kode_risiko);
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('unit', 'like', "%{$search}%")
                        ->orWhere('risiko', 'like', "%{$search}%")
                        ->orWhere('kode_risiko', 'like', "%{$search}%")
                        ->orWhere('sebab', 'like', "%{$search}%")
                        ->orWhere('dampak', 'like', "%{$search}%")
                        ->orWhere('pengendalian', 'like', "%{$search}%")
                        ->orWhere('analisis_tingkat', 'like', "%{$search}%")
                        ->orWhere('target_waktu', 'like', "%{$search}%")
                        ->orWhere('mitigasi_tw1_tingkat', 'like', "%{$search}%")
                        ->orWhere('mitigasi_tw2_tingkat', 'like', "%{$search}%")
                        ->orWhere('mitigasi_tw3_tingkat', 'like', "%{$search}%")
                        ->orWhere('mitigasi_tw4_tingkat', 'like', "%{$search}%");
                });
            }

            $recordsTotal = DaftarRisiko::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'id';
                $orderDir = $request->order[0]['dir'] ?? 'asc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('id', 'asc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('manajemen_risiko', 'update');
            $canRead = PermissionHelper::canAccess('manajemen_risiko', 'read');
            $canDelete = PermissionHelper::canAccess('manajemen_risiko', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'no_urut' => $item->no_urut ?? $item->id,
                    'unit' => $item->unit,
                    'risiko' => $item->risiko,
                    'kode_risiko' => $item->kode_risiko,
                    'sebab' => $item->sebab,
                    'dampak' => $item->dampak,
                    'pengendalian' => $item->pengendalian,
                    'efektif' => (bool) $item->efektif,
                    'tidak_efektif' => (bool) $item->tidak_efektif,
                    'analisis_tingkat' => $item->analisis_tingkat,
                    'analisis_nilai' => $item->analisis_nilai !== null ? (float) $item->analisis_nilai : null,
                    'target_waktu' => $item->target_waktu,
                    'mitigasi_tw1_tingkat' => $item->mitigasi_tw1_tingkat,
                    'mitigasi_tw1_nilai' => $item->mitigasi_tw1_nilai !== null ? (float) $item->mitigasi_tw1_nilai : null,
                    'mitigasi_tw2_tingkat' => $item->mitigasi_tw2_tingkat,
                    'mitigasi_tw2_nilai' => $item->mitigasi_tw2_nilai !== null ? (float) $item->mitigasi_tw2_nilai : null,
                    'mitigasi_tw3_tingkat' => $item->mitigasi_tw3_tingkat,
                    'mitigasi_tw3_nilai' => $item->mitigasi_tw3_nilai !== null ? (float) $item->mitigasi_tw3_nilai : null,
                    'mitigasi_tw4_tingkat' => $item->mitigasi_tw4_tingkat,
                    'mitigasi_tw4_nilai' => $item->mitigasi_tw4_nilai !== null ? (float) $item->mitigasi_tw4_nilai : null,
                    'can_update' => $canUpdate,
                    'can_read' => $canRead,
                    'can_delete' => $canDelete,
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $unitFilter = $request->get('unit');
        $tingkatFilter = $request->get('tingkat');
        $kodeFilter = $request->get('kode_risiko');

        // Distinct options for filters
        $unitOptions = DaftarRisiko::select('unit')->distinct()->orderBy('unit')->pluck('unit');
        $tingkatOptions = ['Sangat Rendah', 'Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'];
        $kodeOptions = DaftarRisiko::select('kode_risiko')->whereNotNull('kode_risiko')->distinct()->orderBy('kode_risiko')->pluck('kode_risiko');

        // Statistics
        $totalRisiko = DaftarRisiko::count();
        $totalTinggiSangatTinggi = DaftarRisiko::whereIn('analisis_tingkat', ['Tinggi', 'Sangat Tinggi'])->count();
        $jumlahUnit = DaftarRisiko::select('unit')->distinct()->count();

        $isFiltered = $request->hasAny(['unit', 'tingkat', 'kode_risiko']);

        return view('pages.komite-mutu.manajemen-risiko.index', compact(
            'unitFilter', 'tingkatFilter', 'kodeFilter',
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

        return redirect(route('komite-mutu.manajemen-risiko.index') . '?saved=1')
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

        return redirect(route('komite-mutu.manajemen-risiko.index') . '?updated=1')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $risiko = DaftarRisiko::findOrFail($id);
        $risiko->delete();

        return redirect(route('komite-mutu.manajemen-risiko.index') . '?deleted=1')
            ->with('success', 'Data berhasil dihapus.');
    }
}
