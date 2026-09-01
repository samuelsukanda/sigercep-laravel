<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanAsetRusak;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class LaporanAsetRusakController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:laporan_aset_rusak,read')->only(['index', 'show']);
        $this->middleware('permission:laporan_aset_rusak,create')->only(['create', 'store']);
        $this->middleware('permission:laporan_aset_rusak,update')->only(['edit', 'update']);
        $this->middleware('permission:laporan_aset_rusak,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'nama_aset', 'lokasi_aset', 'kondisi_aset', 'tanggal'];

            $query = LaporanAsetRusak::query();

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
                        ->orWhere('nama_aset', 'like', "%{$search}%")
                        ->orWhere('lokasi_aset', 'like', "%{$search}%")
                        ->orWhere('kondisi_aset', 'like', "%{$search}%");
                });
            }

            $recordsTotal = LaporanAsetRusak::count();
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

            $canUpdate = PermissionHelper::canAccess('laporan_aset_rusak', 'update');
            $canRead = PermissionHelper::canAccess('laporan_aset_rusak', 'read');
            $canDelete = PermissionHelper::canAccess('laporan_aset_rusak', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'nama_aset' => strtolower($item->nama_aset),
                    'lokasi_aset' => strtolower($item->lokasi_aset),
                    'kondisi_aset' => $item->kondisi_aset,
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

        return view('pages.pengadaan-aset.laporan-aset-rusak.index');
    }

    public function create()
    {
        return view('pages.pengadaan-aset.laporan-aset-rusak.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'nama_aset' => 'required|string|max:50',
            'lokasi_aset' => 'required|string|max:50',
            'kondisi_aset' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status' => 'nullable|in:Rusak Total,Bisa Diperbaiki',
            'foto' => 'required|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $namaFileFoto = 'foto-' . now()->format('YmdHis') . '.' . $foto->getClientOriginalExtension();
            $pathFoto = $foto->storeAs('images/laporan-aset-rusak/foto-barang', $namaFileFoto, 'public');
            $validated['foto'] = $pathFoto;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/laporan-aset-rusak/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        LaporanAsetRusak::create($validated);

        return redirect(route('pengadaan-aset.laporan-aset-rusak.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);
        return view('pages.pengadaan-aset.laporan-aset-rusak.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);
        return view('pages.pengadaan-aset.laporan-aset-rusak.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'nama_aset' => 'required|string|max:50',
            'lokasi_aset' => 'required|string|max:50',
            'kondisi_aset' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'status' => 'nullable|in:Rusak Total,Bisa Diperbaiki',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $pengadaan = LaporanAsetRusak::findOrFail($id);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto && Storage::disk('public')->exists($pengadaan->foto)) {
                Storage::disk('public')->delete($pengadaan->foto);
            }

            $foto = $request->file('foto');
            $namaFileFoto = 'foto-' . now()->format('YmdHis') . '.' . $foto->getClientOriginalExtension();
            $pathFoto = $foto->storeAs('images/laporan-aset-rusak/foto-barang', $namaFileFoto, 'public');
            $validated['foto'] = $pathFoto;
        }

        if ($request->hasFile('foto_barcode')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
                Storage::disk('public')->delete($pengadaan->foto_barcode);
            }

            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/laporan-aset-rusak/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect(route('pengadaan-aset.laporan-aset-rusak.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = LaporanAsetRusak::findOrFail($id);

        if ($pengadaan->foto && Storage::disk('public')->exists($pengadaan->foto)) {
            Storage::disk('public')->delete($pengadaan->foto);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect(route('pengadaan-aset.laporan-aset-rusak.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
