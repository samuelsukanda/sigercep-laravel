<?php

namespace App\Http\Controllers;

use App\Models\BankIlmu;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BankIlmuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank_ilmu,read')->only(['index', 'show']);
        $this->middleware('permission:bank_ilmu,create')->only(['create', 'store']);
        $this->middleware('permission:bank_ilmu,update')->only(['edit', 'update']);
        $this->middleware('permission:bank_ilmu,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['file_pdf', 'created_at'];

            $query = BankIlmu::query();

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

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%");
                });
            }

            $recordsTotal = BankIlmu::count();
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

            $canUpdate = PermissionHelper::canAccess('bank_ilmu', 'update');
            $canRead = PermissionHelper::canAccess('bank_ilmu', 'read');
            $canDelete = PermissionHelper::canAccess('bank_ilmu', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
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
        ]);

        return view('pages.bank-ilmu.index', compact('isFiltered'));
    }

    public function create()
    {
        return view('pages.bank-ilmu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $originalName = $file->getClientOriginalName();
        $folderPath = "bank-ilmu";
        $targetPath = "$folderPath/$originalName";

        if (Storage::disk('public')->exists($targetPath)) {
            return back()->withErrors(['file_pdf' => 'File sudah ada.']);
        }

        Storage::disk('public')->putFileAs($folderPath, $file, $originalName);

        BankIlmu::create([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $bankIlmu = BankIlmu::findOrFail($id);
        return view('pages.bank-ilmu.detail', compact('bankIlmu'));
    }

    public function showFile($id)
    {
        $bankIlmu = BankIlmu::findOrFail($id);

        $filePath = storage_path("app/public/{$bankIlmu->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $bankIlmu->file_pdf . '"'
        ]);
    }

    public function edit(string $id)
    {
        $bankIlmu = BankIlmu::findOrFail($id);
        return view('pages.bank-ilmu.edit', compact('bankIlmu'));
    }

    public function update(Request $request, string $id)
    {
        $bankIlmu = BankIlmu::findOrFail($id);

        $request->validate([
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile
            ? $uploadedFile->getClientOriginalName()
            : $bankIlmu->file_pdf;

        $targetPath = "bank-ilmu/" . $originalName;

        if ($uploadedFile) {
            if (
                $bankIlmu->file_path !== $targetPath &&
                Storage::disk('public')->exists($bankIlmu->file_path)
            ) {
                Storage::disk('public')->delete($bankIlmu->file_path);
            }

            if (Storage::disk('public')->exists($targetPath)) {
                return back()->withErrors(['file_pdf' => 'File dengan nama ini sudah ada.']);
            }

            Storage::disk('public')->putFileAs("bank-ilmu", $uploadedFile, $originalName);
        } else {
            if ($bankIlmu->file_path !== $targetPath) {
                if (!Storage::disk('public')->exists($bankIlmu->file_path)) {
                    return back()->withErrors(['file_pdf' => 'File lama tidak ditemukan.']);
                }

                if (Storage::disk('public')->exists($targetPath)) {
                    return back()->withErrors(['file_pdf' => 'File sudah ada dengan nama tersebut.']);
                }

                $fileContent = Storage::disk('public')->get($bankIlmu->file_path);
                Storage::disk('public')->put($targetPath, $fileContent);
                Storage::disk('public')->delete($bankIlmu->file_path);
            }
        }

        $bankIlmu->update([
            'file_pdf' => $originalName,
            'file_path' => $targetPath,
        ]);

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bankIlmu = BankIlmu::findOrFail($id);

        if (Storage::disk('public')->exists($bankIlmu->file_path)) {
            Storage::disk('public')->delete($bankIlmu->file_path);
        }

        $bankIlmu->delete();

        return redirect()->route('bank-ilmu.index')->with('success', 'Data berhasil dihapus.');
    }
}
