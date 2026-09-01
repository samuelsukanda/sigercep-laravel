<?php

namespace App\Http\Controllers;

use App\Models\DokumenIt;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DokumenITController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:dokumen_it,read')->only(['index', 'show']);
        $this->middleware('permission:dokumen_it,create')->only(['create', 'store']);
        $this->middleware('permission:dokumen_it,update')->only(['edit', 'update']);
        $this->middleware('permission:dokumen_it,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['file_pdf', 'jenis_dokumen', 'created_at'];

            $query = DokumenIt::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('jenis_dokumen')) {
                $query->where('jenis_dokumen', $request->jenis_dokumen);
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%")
                        ->orWhere('jenis_dokumen', 'like', "%{$search}%");
                });
            }

            $recordsTotal = DokumenIt::count();
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

            $canUpdate = PermissionHelper::canAccess('dokumen_it', 'update');
            $canRead = PermissionHelper::canAccess('dokumen_it', 'read');
            $canDelete = PermissionHelper::canAccess('dokumen_it', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'jenis_dokumen' => $item->jenis_dokumen,
                    'created_at_timestamp' => Carbon::parse($item->created_at)->timestamp,
                    'tanggal_formatted' => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->created_at)->translatedFormat('d F Y') . '</span>
                        <span class="text-xs text-gray-400">' . Carbon::parse($item->created_at)->format('H:i') . ' WIB</span>
                    </div>
                ',
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

        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
            'jenis_dokumen'
        ]);

        return view('pages.dokumen-it.index', compact('isFiltered'));
    }

    public function create()
    {
        return view('pages.dokumen-it.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "dokumen-it";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        DokumenIt::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'jenis_dokumen' => $request->jenis_dokumen,
        ]);

        return redirect(route('dokumen-it.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);
        return view('pages.dokumen-it.detail', compact('DokumenIt'));
    }

    public function showFile($id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        $filePath = storage_path("app/public/{$DokumenIt->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $DokumenIt->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);
        return view('pages.dokumen-it.edit', compact('DokumenIt'));
    }

    public function update(Request $request, string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $DokumenIt->file_pdf;

        $targetPath = "dokumen-it/" . $originalName;

        if ($uploadedFile) {
            if (
                $DokumenIt->file_path !== $targetPath &&
                Storage::disk('public')->exists($DokumenIt->file_path)
            ) {
                Storage::disk('public')->delete($DokumenIt->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("dokumen-it", $uploadedFile, $originalName);
        } else {
            if ($DokumenIt->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($DokumenIt->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($DokumenIt->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($DokumenIt->file_path);
            }
        }

        $DokumenIt->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
            'jenis_dokumen' => $request->jenis_dokumen,
        ]);

        return redirect(route('dokumen-it.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $DokumenIt = DokumenIt::findOrFail($id);

        if (Storage::disk('public')->exists($DokumenIt->file_path)) {
            Storage::disk('public')->delete($DokumenIt->file_path);
        }

        $DokumenIt->delete();

        return redirect(route('dokumen-it.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
