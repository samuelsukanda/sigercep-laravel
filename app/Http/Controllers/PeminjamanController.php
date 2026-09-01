<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class PeminjamanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:peminjaman,read')->only(['index', 'show']);
        $this->middleware('permission:peminjaman,create')->only(['create', 'store']);
        $this->middleware('permission:peminjaman,update')->only(['edit', 'update']);
        $this->middleware('permission:peminjaman,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'tanggal', 'barang', 'status'];

            $query = Peminjaman::query();

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
                        ->orWhere('barang', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Peminjaman::count();
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

            $canUpdate = PermissionHelper::canAccess('peminjaman', 'update');
            $canRead = PermissionHelper::canAccess('peminjaman', 'read');
            $canDelete = PermissionHelper::canAccess('peminjaman', 'delete');

            $data = [];

            foreach ($records as $item) {
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'barang' => strtolower($item->barang),
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

        return view('pages.peminjaman.index');
    }

    public function create()
    {
        return view('pages.peminjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'barang' => 'required|string|max:50',
            'tanda_tangan' => 'required|string',
            'status' => 'nullable|in:Sudah Di Kembalikan,Belum Di Kembalikan',
        ]);

        if ($request->has('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/peminjaman/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            $validated['tanda_tangan'] = 'storage/' . $path;
        }

        Peminjaman::create($validated);

        return redirect(route('peminjaman.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.peminjaman.detail', compact('peminjaman'));
    }

    public function edit(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'barang' => 'required|string|max:50',
            'tanda_tangan' => 'nullable|string',
            'status' => 'nullable|in:Sudah Di Kembalikan,Belum Di Kembalikan',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        if ($request->filled('tanda_tangan')) {
            $signatureData = $request->input('tanda_tangan');
            $data = explode(',', $signatureData);
            $decoded = base64_decode($data[1]);

            $path = 'signatures/peminjaman/signature_' . time() . '.png';

            Storage::disk('public')->put($path, $decoded);

            if ($peminjaman->tanda_tangan && Storage::disk('public')->exists(str_replace('storage/', '', $peminjaman->tanda_tangan))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $peminjaman->tanda_tangan));
            }

            $validated['tanda_tangan'] = 'storage/' . $path;
        } else {
            $validated['tanda_tangan'] = $peminjaman->tanda_tangan;
        }

        $peminjaman->update($validated);

        return redirect(route('peminjaman.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->tanda_tangan) {
            $signaturePath = str_replace('storage/', '', $peminjaman->tanda_tangan);
            if (Storage::disk('public')->exists($signaturePath)) {
                Storage::disk('public')->delete($signaturePath);
            }
        }

        $peminjaman->delete();

        return redirect(route('peminjaman.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
