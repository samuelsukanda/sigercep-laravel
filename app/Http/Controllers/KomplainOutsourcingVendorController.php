<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomplainOutsourcingVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PermissionHelper;

class KomplainOutsourcingVendorController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:outsourcing_vendor,read')->only(['index', 'show']);
        $this->middleware('permission:outsourcing_vendor,create')->only(['create', 'store']);
        $this->middleware('permission:outsourcing_vendor,update')->only(['edit', 'update']);
        $this->middleware('permission:outsourcing_vendor,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'tujuan_unit', 'area', 'jam', 'tanggal', 'kendala', 'status'];

            $query = KomplainOutsourcingVendor::query();

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
                        ->orWhere('area', 'like', "%{$search}%")
                        ->orWhere('kendala', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = KomplainOutsourcingVendor::count();
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

            $canUpdate = PermissionHelper::canAccess('outsourcing_vendor', 'update');
            $canRead = PermissionHelper::canAccess('outsourcing_vendor', 'read');
            $canDelete = PermissionHelper::canAccess('outsourcing_vendor', 'delete');

            $data = [];

            foreach ($records as $item) {
                $jam = '-';
                if (!empty($item->jam)) {
                    try {
                        $jam = Carbon::createFromFormat('H:i:s', $item->jam)->format('H:i');
                    } catch (\Exception $e) {
                        $jam = $item->jam;
                    }
                }
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(str_replace('.', ' ', $item->nama ?? '-')),
                    'unit' => $item->unit,
                    'tujuan_unit' => $item->tujuan_unit,
                    'area' => $item->area ?? '-',
                    'jam' => $jam,
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

        return view('pages.komplain.outsourcing-vendor.index');
    }

    public function create()
    {
        return view('pages.komplain.outsourcing-vendor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tujuan_unit' => 'required|string|max:50',
            'jam' => 'required',
            'tanggal' => 'required|date',
            'kendala' => 'required|string',
            'area' => 'required|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'komplain-outsourcing-vendor-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-outsourcing-vendor', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        KomplainOutsourcingVendor::create($validated);

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);
        return view('pages.komplain.outsourcing-vendor.edit', compact('komplain'));
    }

    public function show($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);
        return view('pages.komplain.outsourcing-vendor.detail', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'tujuan_unit' => 'nullable|string|max:50',
            'jam' => 'nullable',
            'tanggal' => 'nullable|date',
            'kendala' => 'nullable|string',
            'area' => 'nullable|string|max:100',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,In Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KomplainOutsourcingVendor::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
                Storage::disk('public')->delete($komplain->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'komplain-outsourcing-vendor-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/komplain-outsourcing-vendor', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $komplain->update($validated);

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KomplainOutsourcingVendor::findOrFail($id);

        if ($komplain->foto && Storage::disk('public')->exists($komplain->foto)) {
            Storage::disk('public')->delete($komplain->foto);
        }

        $komplain->delete();

        return redirect()->route('komplain.outsourcing-vendor.index')->with('success', 'Data berhasil dihapus.');
    }
}
