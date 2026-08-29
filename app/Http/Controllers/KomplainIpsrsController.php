<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainIpsrs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PermissionHelper;

class KomplainIpsrsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:komplain_ipsrs,read')->only(['index', 'show']);
        $this->middleware('permission:komplain_ipsrs,create')->only(['create', 'store']);
        $this->middleware('permission:komplain_ipsrs,update')->only(['edit', 'update']);
        $this->middleware('permission:komplain_ipsrs,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'tujuan_unit', 'tanggal', 'kendala', 'status'];

            $query = KomplainIpsrs::query();

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
                        ->orWhere('tujuan_unit', 'like', "%{$search}%")
                        ->orWhere('kendala', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = KomplainIpsrs::count();
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

            $canUpdate = PermissionHelper::canAccess('komplain_ipsrs', 'update');
            $canRead = PermissionHelper::canAccess('komplain_ipsrs', 'read');
            $canDelete = PermissionHelper::canAccess('komplain_ipsrs', 'delete');

            $data = [];

            foreach ($records as $item) {
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(str_replace('.', ' ', $item->nama ?? '-')),
                    'unit' => $item->unit,
                    'tujuan_unit' => $item->tujuan_unit,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'kendala' => strtolower($item->kendala),
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
                'data' => $data
            ]);
        }

        return view('pages.komplain.ipsrs.index');
    }

    public function create()
    {
        return view('pages.komplain.ipsrs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'komplain-ipsrs-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-ipsrs', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        KomplainIpsrs::create($validated);

        return redirect(route('komplain.ipsrs.index') . '?deleted=1')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        return view('pages.komplain.ipsrs.edit', compact('komplain'));
    }

    public function show($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);
        return view('pages.komplain.ipsrs.detail', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'tujuan_unit' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'kendala' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,In Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KomplainIpsrs::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
                Storage::disk('public')->delete($komplain->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'komplain-ipsrs-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-ipsrs', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $komplain->update($validated);

        return redirect(route('komplain.ipsrs.index') . '?deleted=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KomplainIpsrs::findOrFail($id);

        if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
            Storage::disk('public')->delete($komplain->foto);
        }

        $komplain->delete();

        return redirect(route('komplain.ipsrs.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}