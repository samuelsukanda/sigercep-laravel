<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesainGrafis;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class DesainGrafisController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:desain_grafis,read')->only(['index', 'show']);
        $this->middleware('permission:desain_grafis,create')->only(['create', 'store']);
        $this->middleware('permission:desain_grafis,update')->only(['edit', 'update']);
        $this->middleware('permission:desain_grafis,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['nama', 'unit', 'keperluan', 'desain', 'tanggal', 'status'];

            $query = DesainGrafis::query();

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
                        ->orWhere('keperluan', 'like', "%{$search}%")
                        ->orWhere('desain', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = DesainGrafis::count();
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

            $canUpdate = PermissionHelper::canAccess('desain_grafis', 'update');
            $canRead = PermissionHelper::canAccess('desain_grafis', 'read');
            $canDelete = PermissionHelper::canAccess('desain_grafis', 'delete');

            $data = [];

            foreach ($records as $item) {
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'keperluan' => strtolower($item->keperluan),
                    'desain' => $item->desain,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'status_badge' => $statusBadge,
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

        return view('pages.desain-grafis.index');
    }

    public function create()
    {
        return view('pages.desain-grafis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'desain' => 'required|string|max:50',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'panjang' => 'nullable|string|max:50',
            'tinggi' => 'nullable|string|max:50',
            'satuan' => 'nullable|string|max:50',
            'menit' => 'nullable|string|max:50',
            'detik' => 'nullable|string|max:50',
        ]);

        if ($validated['desain'] === 'Video') {
            $validated['panjang'] = null;
            $validated['tinggi'] = null;
            $validated['satuan'] = null;
        } else {
            $validated['menit'] = null;
            $validated['detik'] = null;
        }

        DesainGrafis::create($validated);

        return redirect(route('desain-grafis.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show($id)
    {
        $desain = DesainGrafis::findOrFail($id);
        return view('pages.desain-grafis.detail', compact('desain'));
    }

    public function edit($id)
    {
        $desain = DesainGrafis::findOrFail($id);
        return view('pages.desain-grafis.edit', compact('desain'));
    }

    public function update(Request $request, $id)
    {
        $desain = DesainGrafis::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'keperluan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'desain' => 'required|string|max:50',
            'status' => 'nullable|in:Pending,On Progress,Done',
            'panjang' => 'nullable|string|max:50',
            'tinggi' => 'nullable|string|max:50',
            'satuan' => 'nullable|string|max:50',
            'menit' => 'nullable|string|max:50',
            'detik' => 'nullable|string|max:50',
        ]);

        $updateData = $validated;

        if ($validated['desain'] === 'Video') {
            $updateData['panjang'] = null;
            $updateData['tinggi'] = null;
            $updateData['satuan'] = null;
        } else {
            $updateData['menit'] = null;
            $updateData['detik'] = null;
        }

        $desain->update($updateData);

        return redirect(route('desain-grafis.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $desain = DesainGrafis::findOrFail($id);
        $desain->delete();

        return redirect(route('desain-grafis.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
