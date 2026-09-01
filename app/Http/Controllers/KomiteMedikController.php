<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KomiteMedik;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class KomiteMedikController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:komite_medik,read')->only(['index', 'show']);
        $this->middleware('permission:komite_medik,create')->only(['create', 'store']);
        $this->middleware('permission:komite_medik,update')->only(['edit', 'update']);
        $this->middleware('permission:komite_medik,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['file_pdf', 'unit', 'created_at'];

            $query = KomiteMedik::query();

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

            if ($request->filled('unit')) {
                $query->where('unit', $request->unit);
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%");
                });
            }

            $recordsTotal = KomiteMedik::count();
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

            $canUpdate = PermissionHelper::canAccess('komite_medik', 'update');
            $canRead = PermissionHelper::canAccess('komite_medik', 'read');
            $canDelete = PermissionHelper::canAccess('komite_medik', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'unit' => $item->unit,
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

        return view('pages.komite-medik.index');
    }

    public function create()
    {
        return view('pages.komite-medik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "komite-medik";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        KomiteMedik::create([
            'unit' => $unit,
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect(route('komite-medik.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);
        return view('pages.komite-medik.detail', compact('komiteMedik'));
    }

    public function showFile($id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        $filePath = storage_path("app/public/{$komiteMedik->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $komiteMedik->unit . '-' . $komiteMedik->file_pdf . '"'
        ]);
    }


    public function edit(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);
        return view('pages.komite-medik.edit', compact('komiteMedik'));
    }

    public function update(Request $request, string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $komiteMedik->file_pdf;

        $targetPath = "komite-medik/" . $originalName;

        if ($uploadedFile) {
            if ($komiteMedik->file_path !== $targetPath && Storage::disk('public')->exists($komiteMedik->file_path)) {
                Storage::disk('public')->delete($komiteMedik->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("komite-medik/", $uploadedFile, $originalName);
        } else {
            if ($komiteMedik->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($komiteMedik->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($komiteMedik->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($komiteMedik->file_path);
            }
        }

        $komiteMedik->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'unit' => $unit,
        ]);

        return redirect(route('komite-medik.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $komiteMedik = KomiteMedik::findOrFail($id);

        if (Storage::disk('public')->exists($komiteMedik->file_path)) {
            Storage::disk('public')->delete($komiteMedik->file_path);
        }

        $komiteMedik->delete();

        return redirect(route('komite-medik.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
