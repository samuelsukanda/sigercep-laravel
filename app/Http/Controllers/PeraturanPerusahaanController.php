<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PeraturanPerusahaan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class PeraturanPerusahaanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:peraturan_perusahaan,read')->only(['index', 'show']);
        $this->middleware('permission:peraturan_perusahaan,create')->only(['create', 'store']);
        $this->middleware('permission:peraturan_perusahaan,update')->only(['edit', 'update']);
        $this->middleware('permission:peraturan_perusahaan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['file_pdf', 'created_at'];

            $query = PeraturanPerusahaan::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->whereDate('created_at', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->whereDate('created_at', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%");
                });
            }

            $recordsTotal = PeraturanPerusahaan::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('peraturan_perusahaan', 'update');
            $canRead = PermissionHelper::canAccess('peraturan_perusahaan', 'read');
            $canDelete = PermissionHelper::canAccess('peraturan_perusahaan', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'created_at_timestamp' => Carbon::parse($item->created_at)->timestamp,
                    'created_at_formatted' => Carbon::parse($item->created_at)->translatedFormat('d F Y'),
                    'created_at_time' => Carbon::parse($item->created_at)->format('H:i'),
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

        return view('pages.sdm-hukum.peraturan-perusahaan.index');
    }

    public function create()
    {
        return view('pages.sdm-hukum.peraturan-perusahaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "peraturan-perusahaan";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        PeraturanPerusahaan::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);
        return view('pages.sdm-hukum.peraturan-perusahaan.detail', compact('peraturanPerusahaan'));
    }

    public function showFile($id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        $filePath = storage_path("app/public/{$peraturanPerusahaan->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $peraturanPerusahaan->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);
        return view('pages.sdm-hukum.peraturan-perusahaan.edit', compact('peraturanPerusahaan'));
    }

    public function update(Request $request, string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $peraturanPerusahaan->file_pdf;

        $targetPath = "peraturan-perusahaan/" . $originalName;

        if ($uploadedFile) {
            if (
                $peraturanPerusahaan->file_path !== $targetPath &&
                Storage::disk('public')->exists($peraturanPerusahaan->file_path)
            ) {
                Storage::disk('public')->delete($peraturanPerusahaan->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("peraturan-perusahaan", $uploadedFile, $originalName);
        } else {
            if ($peraturanPerusahaan->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($peraturanPerusahaan->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($peraturanPerusahaan->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($peraturanPerusahaan->file_path);
            }
        }

        $peraturanPerusahaan->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $peraturanPerusahaan = PeraturanPerusahaan::findOrFail($id);

        if (Storage::disk('public')->exists($peraturanPerusahaan->file_path)) {
            Storage::disk('public')->delete($peraturanPerusahaan->file_path);
        }

        $peraturanPerusahaan->delete();

        return redirect()->route('sdm-hukum.peraturan-perusahaan.index')->with('success', 'Data berhasil dihapus.');
    }
}
