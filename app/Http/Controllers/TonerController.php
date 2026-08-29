<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toner;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class TonerController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:toner,read')->only(['index', 'show']);
        $this->middleware('permission:toner,create')->only(['create', 'store']);
        $this->middleware('permission:toner,update')->only(['edit', 'update']);
        $this->middleware('permission:toner,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['nama', 'unit', 'toner', 'jumlah', 'tanggal'];

            $query = Toner::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->whereDate('tanggal', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->whereDate('tanggal', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('toner', 'like', "%{$search}%")
                        ->orWhere('jumlah', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Toner::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('toner', 'update');
            $canRead = PermissionHelper::canAccess('toner', 'read');
            $canDelete = PermissionHelper::canAccess('toner', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'toner' => $item->toner,
                    'jumlah' => $item->jumlah,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
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

        return view('pages.toner.index');
    }

    public function create()
    {
        return view('pages.toner.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'toner' => 'required|string|max:50',
            'jumlah' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'tanda_tangan' => 'required|string',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/toner/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        Toner::create($validated);

        return redirect(route('toner.index') . '?deleted=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $toner = Toner::findOrFail($id);
        return view('pages.toner.detail', compact('toner'));
    }

    public function edit(string $id)
    {
        $toner = Toner::findOrFail($id);
        return view('pages.toner.edit', compact('toner'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'toner' => 'required|string|max:50',
            'jumlah' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'tanda_tangan' => 'nullable|string',
        ]);

        $toner = Toner::findOrFail($id);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/toner/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            if ($toner->tanda_tangan && Storage::disk('public')->exists(str_replace('storage/', '', $toner->tanda_tangan))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $toner->tanda_tangan));
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $toner->tanda_tangan;
        }

        $toner->update($validated);

        return redirect(route('toner.index') . '?deleted=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $toner = Toner::findOrFail($id);

        if ($toner->tanda_tangan) {
            $signaturePath = str_replace('storage/', '', $toner->tanda_tangan);
            if (Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
        }

        $toner->delete();

        return redirect(route('toner.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
