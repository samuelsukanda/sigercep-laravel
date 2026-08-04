<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesehatanLingkungan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PermissionHelper;

class KesehatanLingkunganController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:kesehatan_lingkungan,read')->only(['index', 'show']);
        $this->middleware('permission:kesehatan_lingkungan,create')->only(['create', 'store']);
        $this->middleware('permission:kesehatan_lingkungan,update')->only(['edit', 'update']);
        $this->middleware('permission:kesehatan_lingkungan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'tanggal', 'lokasi_masalah', 'jenis_hama', 'status'];

            $query = KesehatanLingkungan::query();

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
                        ->orWhere('lokasi_masalah', 'like', "%{$search}%")
                        ->orWhere('jenis_hama', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = KesehatanLingkungan::count();
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

            $canUpdate = PermissionHelper::canAccess('kesehatan_lingkungan', 'update');
            $canRead = PermissionHelper::canAccess('kesehatan_lingkungan', 'read');
            $canDelete = PermissionHelper::canAccess('kesehatan_lingkungan', 'delete');

            $data = [];

            foreach ($records as $item) {
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(str_replace('.', ' ', $item->nama ?? '-')),
                    'unit' => $item->unit,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'lokasi_masalah' => strtolower($item->lokasi_masalah),
                    'jenis_hama' => strtolower($item->jenis_hama),
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

        return view('pages.komplain.kesehatan-lingkungan.index');
    }

    public function create()
    {
        return view('pages.komplain.kesehatan-lingkungan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'lokasi_masalah' => 'required|string',
            'jenis_hama' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $namaFile = 'kesehatan-lingkungan-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/kesehatan-lingkungan', $namaFile, 'public');
            $validated['dokumentasi'] = $path;
        }

        KesehatanLingkungan::create($validated);

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);
        return view('pages.komplain.kesehatan-lingkungan.detail', compact('komplain'));
    }

    public function edit(string $id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);
        return view('pages.komplain.kesehatan-lingkungan.edit', compact('komplain'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'tanggal' => 'nullable|date',
            'lokasi_masalah' => 'nullable|string',
            'jenis_hama' => 'nullable|string',
            'dokumentasi' => 'nullable|image|mimes:jpg,jpeg,png',
            'status' => 'nullable|in:Pending,In Progress,Done',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $komplain = KesehatanLingkungan::findOrFail($id);

        if ($request->hasFile('dokumentasi')) {
            if ($komplain->dokumentasi && Storage::disk('public')->exists($komplain->dokumentasi)) {
                Storage::disk('public')->delete($komplain->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $namaFile = 'kesehatan-lingkungan-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/kesehatan-lingkungan', $namaFile, 'public');
            $validated['dokumentasi'] = $path;
        }

        $komplain->update($validated);

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komplain = KesehatanLingkungan::findOrFail($id);

        if ($komplain->dokumentasi && Storage::disk('public')->exists($komplain->dokumentasi)) {
            Storage::disk('public')->delete($komplain->dokumentasi);
        }

        $komplain->delete();

        return redirect()->route('komplain.kesehatan-lingkungan.index')->with('success', 'Data berhasil dihapus.');
    }
}
