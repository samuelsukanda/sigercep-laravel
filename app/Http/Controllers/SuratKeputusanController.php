<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeputusan;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class SuratKeputusanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:surat_keputusan,read')->only(['index', 'show']);
        $this->middleware('permission:surat_keputusan,create')->only(['create', 'store']);
        $this->middleware('permission:surat_keputusan,update')->only(['edit', 'update']);
        $this->middleware('permission:surat_keputusan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['file_pdf', 'unit', 'created_at'];

            $query = SuratKeputusan::query();

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

            if ($request->filled('nama_file')) {
                $query->where('file_pdf', 'like', '%' . $request->nama_file . '%');
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

            $recordsTotal = SuratKeputusan::count();
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

            $canUpdate = PermissionHelper::canAccess('surat_keputusan', 'update');
            $canRead = PermissionHelper::canAccess('surat_keputusan', 'read');
            $canDelete = PermissionHelper::canAccess('surat_keputusan', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'unit' => $item->unit,
                    'created_at_timestamp' => Carbon::parse($item->created_at)->timestamp,
                    'created_at_formatted' => Carbon::parse($item->created_at)->translatedFormat('d F Y'),
                    'created_at_time' => Carbon::parse($item->created_at)->format('H:i') . ' WIB',
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

        return view('pages.sdm-hukum.surat-keputusan.index');
    }

    public function create()
    {
        return view('pages.sdm-hukum.surat-keputusan.create');
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
        $folderPath = "surat-keputusan";
        $targetPath = "$folderPath/$originalName";

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada di unit ini.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        SuratKeputusan::create([
            'unit' => $unit,
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);
        return view('pages.sdm-hukum.surat-keputusan.detail', compact('suratKeputusan'));
    }

    public function showFile($id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        $filePath = storage_path("app/public/{$suratKeputusan->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $suratKeputusan->unit . '-' . $suratKeputusan->file_pdf . '"'
        ]);
    }


    public function edit(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);
        return view('pages.sdm-hukum.surat-keputusan.edit', compact('suratKeputusan'));
    }

    public function update(Request $request, string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        $request->validate([
            'unit' => 'required|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unit = $request->unit;
        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $suratKeputusan->file_pdf;

        $targetPath = "surat-keputusan/" . $originalName;

        if ($uploadedFile) {
            if ($suratKeputusan->file_path !== $targetPath && Storage::disk('public')->exists($suratKeputusan->file_path)) {
                Storage::disk('public')->delete($suratKeputusan->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
            }

            Storage::disk('public')->putFileAs("surat-keputusan/", $uploadedFile, $originalName);
        } else {
            if ($suratKeputusan->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($suratKeputusan->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada untuk unit ini.']);
                }

                $fileContent = Storage::disk('public')->get($suratKeputusan->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);

                Storage::disk('public')->delete($suratKeputusan->file_path);
            }
        }

        $suratKeputusan->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'unit' => $unit,
        ]);

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $suratKeputusan = SuratKeputusan::findOrFail($id);

        if (Storage::disk('public')->exists($suratKeputusan->file_path)) {
            Storage::disk('public')->delete($suratKeputusan->file_path);
        }

        $suratKeputusan->delete();

        return redirect()->route('sdm-hukum.surat-keputusan.index')->with('success', 'Data berhasil dihapus.');
    }
}