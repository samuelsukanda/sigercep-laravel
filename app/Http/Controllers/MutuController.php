<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutu;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class MutuController extends Controller
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
        if ($request->ajax()) {
            $columns = ['indikator', 'periode', 'unit', 'pj_data', 'numerator', 'penumerator', 'capaian'];

            $query = Mutu::query();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('indikator', 'like', "%{$search}%")
                        ->orWhere('periode', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('pj_data', 'like', "%{$search}%")
                        ->orWhere('numerator', 'like', "%{$search}%")
                        ->orWhere('penumerator', 'like', "%{$search}%")
                        ->orWhere('capaian', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Mutu::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'id';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('id', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('mutu', 'update');
            $canRead = PermissionHelper::canAccess('mutu', 'read');
            $canDelete = PermissionHelper::canAccess('mutu', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'indikator' => Str::limit($item->indikator, 50),
                    'periode' => $item->periode,
                    'unit' => $item->unit,
                    'pj_data' => $item->pj_data,
                    'numerator' => $item->numerator,
                    'penumerator' => $item->penumerator,
                    'capaian' => $item->capaian,
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

        return view('pages.komite-mutu.mutu.index');
    }

    public function create()
    {
        return view('pages.komite-mutu.mutu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'indikator' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'unit' => 'required|string|max:100',
            'pj_data' => 'required|string|max:100',
            'numerator' => 'required|string|max:100',
            'penumerator' => 'required|string|max:100',
            'capaian' => 'required|string|max:100',
        ]);

        Mutu::create($validated);

        return redirect(route('komite-mutu.mutu.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        return view('pages.komite-mutu.mutu.detail', compact('mutu'));
    }

    public function edit(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        return view('pages.komite-mutu.mutu.edit', compact('mutu'));
    }

    public function update(Request $request, $id)
    {
        $mutu = Mutu::findOrFail($id);

        $validated = $request->validate([
            'indikator' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'unit' => 'required|string|max:100',
            'pj_data' => 'required|string|max:100',
            'numerator' => 'required|string|max:100',
            'penumerator' => 'required|string|max:100',
            'capaian' => 'required|string|max:100',
        ]);

        $mutu->update($validated);

        return redirect(route('komite-mutu.mutu.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mutu = Mutu::findOrFail($id);
        $mutu->delete();

        return redirect(route('komite-mutu.mutu.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
