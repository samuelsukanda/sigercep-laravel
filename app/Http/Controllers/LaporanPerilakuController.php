<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanPerilaku;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class LaporanPerilakuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan_perilaku,read')->only(['index', 'show']);
        $this->middleware('permission:laporan_perilaku,create')->only(['create', 'store']);
        $this->middleware('permission:laporan_perilaku,update')->only(['edit', 'update']);
        $this->middleware('permission:laporan_perilaku,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['nama', 'nik', 'unit', 'tanggal', 'kategori_laporan', 'keterangan_perilaku'];

            $query = LaporanPerilaku::query();

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
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('kategori_laporan', 'like', "%{$search}%")
                        ->orWhere('keterangan_perilaku', 'like', "%{$search}%");
                });
            }

            $recordsTotal = LaporanPerilaku::count();
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

            $canUpdate = PermissionHelper::canAccess('laporan_perilaku', 'update');
            $canRead = PermissionHelper::canAccess('laporan_perilaku', 'read');
            $canDelete = PermissionHelper::canAccess('laporan_perilaku', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'nik' => $item->nik,
                    'unit' => $item->unit,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'kategori_laporan' => $item->kategori_laporan,
                    'keterangan_perilaku' => Str::limit($item->keterangan_perilaku, 50),
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

        return view('pages.komite-mutu.laporan-perilaku.index');
    }

    public function create()
    {
        return view('pages.komite-mutu.laporan-perilaku.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kategori_laporan' => 'required|string|max:255',
            'keterangan_perilaku' => 'required|string|max:255',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();

        $validated['file_pdf'] = $originalName;
        $validated['file_path'] = 'laporan-perilaku/' . $originalName;

        if (Storage::disk('public')->exists($validated['file_path'])) {
            return back()->withErrors([
                'file_pdf' => 'File dengan nama ini sudah ada.'
            ]);
        }

        Storage::disk('public')->putFileAs(
            'laporan-perilaku',
            $file,
            $originalName
        );

        LaporanPerilaku::create($validated);

        return redirect()
            ->route('komite-mutu.laporan-perilaku.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);
        return view('pages.komite-mutu.laporan-perilaku.detail', compact('laporanPerilaku'));
    }

    public function showFile($id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        $filePath = storage_path("app/public/{$laporanPerilaku->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $laporanPerilaku->unit . '-' . $laporanPerilaku->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);
        return view('pages.komite-mutu.laporan-perilaku.edit', compact('laporanPerilaku'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:255',
            'unit' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kategori_laporan' => 'required|string|max:255',
            'keterangan_perilaku' => 'required|string|max:255',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        if ($request->hasFile('file_pdf')) {
            if (
                $laporanPerilaku->file_path &&
                Storage::disk('public')->exists($laporanPerilaku->file_path)
            ) {
                Storage::disk('public')->delete($laporanPerilaku->file_path);
            }

            $file = $request->file('file_pdf');
            $originalName = $file->getClientOriginalName();
            $validated['file_pdf'] = $originalName;
            $validated['file_path'] = 'laporan-perilaku/' . $originalName;

            Storage::disk('public')->putFileAs(
                'laporan-perilaku',
                $file,
                $originalName
            );
        }

        $laporanPerilaku->update($validated);

        return redirect()
            ->route('komite-mutu.laporan-perilaku.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $laporanPerilaku = LaporanPerilaku::findOrFail($id);

        if (Storage::disk('public')->exists($laporanPerilaku->file_path)) {
            Storage::disk('public')->delete($laporanPerilaku->file_path);
        }

        $laporanPerilaku->delete();

        return redirect(route('komite-mutu.laporan-perilaku.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
