<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MandatoryTraining;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class MandatoryTrainingController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:mandatory_training,read')->only(['index', 'show']);
        $this->middleware('permission:mandatory_training,create')->only(['create', 'store']);
        $this->middleware('permission:mandatory_training,update')->only(['edit', 'update']);
        $this->middleware('permission:mandatory_training,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['file_pdf', 'created_at'];

            $query = MandatoryTraining::query();

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
                $query->where('file_pdf', 'like', "%{$search}%");
            }

            $recordsTotal = MandatoryTraining::count();
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

            $canUpdate = PermissionHelper::canAccess('mandatory_training', 'update');
            $canRead = PermissionHelper::canAccess('mandatory_training', 'read');
            $canDelete = PermissionHelper::canAccess('mandatory_training', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'tanggal_timestamp' => Carbon::parse($item->created_at)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->created_at)->translatedFormat('d F Y'),
                    'tanggal_time' => Carbon::parse($item->created_at)->format('H:i'),
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

        return view('pages.sdm-hukum.mandatory-training.index');
    }

    public function create()
    {
        return view('pages.sdm-hukum.mandatory-training.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "mandatory-training";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        MandatoryTraining::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect(route('sdm-hukum.mandatory-training.index') . '?deleted=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);
        return view('pages.sdm-hukum.mandatory-training.detail', compact('mandatoryTraining'));
    }

    public function showFile($id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        $filePath = storage_path("app/public/{$mandatoryTraining->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $mandatoryTraining->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);
        return view('pages.sdm-hukum.mandatory-training.edit', compact('mandatoryTraining'));
    }

    public function update(Request $request, string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $mandatoryTraining->file_pdf;

        $targetPath = "mandatory-training/" . $originalName;

        if ($uploadedFile) {
            if (
                $mandatoryTraining->file_path !== $targetPath &&
                Storage::disk('public')->exists($mandatoryTraining->file_path)
            ) {
                Storage::disk('public')->delete($mandatoryTraining->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("mandatory-training", $uploadedFile, $originalName);
        } else {
            if ($mandatoryTraining->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($mandatoryTraining->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($mandatoryTraining->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($mandatoryTraining->file_path);
            }
        }

        $mandatoryTraining->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect(route('sdm-hukum.mandatory-training.index') . '?deleted=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $mandatoryTraining = MandatoryTraining::findOrFail($id);

        if (Storage::disk('public')->exists($mandatoryTraining->file_path)) {
            Storage::disk('public')->delete($mandatoryTraining->file_path);
        }

        $mandatoryTraining->delete();

        return redirect(route('sdm-hukum.mandatory-training.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
