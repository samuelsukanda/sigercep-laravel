<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PemindahanAset;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class PemindahanAsetController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pemindahan_aset,read')->only(['index', 'show']);
        $this->middleware('permission:pemindahan_aset,create')->only(['create', 'store']);
        $this->middleware('permission:pemindahan_aset,update')->only(['edit', 'update']);
        $this->middleware('permission:pemindahan_aset,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit_asal', 'unit_tujuan', 'keperluan', 'tanggal', 'nama_barang'];

            $query = PemindahanAset::query();

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
                        ->orWhere('unit_asal', 'like', "%{$search}%")
                        ->orWhere('unit_tujuan', 'like', "%{$search}%")
                        ->orWhere('keperluan', 'like', "%{$search}%")
                        ->orWhere('nama_barang', 'like', "%{$search}%");
                });
            }

            $recordsTotal = PemindahanAset::count();
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

            $canUpdate = PermissionHelper::canAccess('pemindahan_aset', 'update');
            $canRead = PermissionHelper::canAccess('pemindahan_aset', 'read');
            $canDelete = PermissionHelper::canAccess('pemindahan_aset', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit_asal' => $item->unit_asal,
                    'unit_tujuan' => $item->unit_tujuan,
                    'keperluan' => Str::limit(strtolower($item->keperluan), 40),
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'nama_barang' => strtolower($item->nama_barang),
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

        return view('pages.pengadaan-aset.pemindahan-aset.index');
    }

    public function create()
    {
        return view('pages.pengadaan-aset.pemindahan-aset.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit_asal' => 'required|string|max:50',
            'unit_tujuan' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'foto_barang' => 'required|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        if ($request->hasFile('foto_barang')) {
            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pemindahan-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pemindahan-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        PemindahanAset::create($validated);

        return redirect(route('pengadaan-aset.pemindahan-aset.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);
        return view('pages.pengadaan-aset.pemindahan-aset.detail', compact('pengadaan'));
    }

    public function edit(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);
        return view('pages.pengadaan-aset.pemindahan-aset.edit', compact('pengadaan'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit_asal' => 'required|string|max:50',
            'unit_tujuan' => 'required|string|max:50',
            'keperluan' => 'required|string',
            'tanggal' => 'required|date',
            'nama_barang' => 'required|string|max:50',
            'foto_barang' => 'nullable|image|mimes:jpg,jpeg,png',
            'foto_barcode' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $pengadaan = PemindahanAset::findOrFail($id);

        if ($request->hasFile('foto_barang')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
                Storage::disk('public')->delete($pengadaan->foto_barang);
            }

            $fileBarang = $request->file('foto_barang');
            $namaFileBarang = 'foto-barang-' . now()->format('YmdHis') . '.' . $fileBarang->getClientOriginalExtension();
            $pathBarang = $fileBarang->storeAs('images/pemindahan-aset/foto-barang', $namaFileBarang, 'public');
            $validated['foto_barang'] = $pathBarang;
        }

        if ($request->hasFile('foto_barcode')) {
            // Hapus foto lama jika ada
            if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
                Storage::disk('public')->delete($pengadaan->foto_barcode);
            }

            $fileBarcode = $request->file('foto_barcode');
            $namaFileBarcode = 'foto-barcode-' . now()->format('YmdHis') . '.' . $fileBarcode->getClientOriginalExtension();
            $pathBarcode = $fileBarcode->storeAs('images/pemindahan-aset/foto-barcode', $namaFileBarcode, 'public');
            $validated['foto_barcode'] = $pathBarcode;
        }

        $pengadaan->update($validated);

        return redirect(route('pengadaan-aset.pemindahan-aset.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengadaan = PemindahanAset::findOrFail($id);

        if ($pengadaan->foto_barang && Storage::disk('public')->exists($pengadaan->foto_barang)) {
            Storage::disk('public')->delete($pengadaan->foto_barang);
        }

        if ($pengadaan->foto_barcode && Storage::disk('public')->exists($pengadaan->foto_barcode)) {
            Storage::disk('public')->delete($pengadaan->foto_barcode);
        }

        $pengadaan->delete();

        return redirect(route('pengadaan-aset.pemindahan-aset.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
