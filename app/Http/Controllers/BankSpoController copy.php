<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankSpo;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;

class BankSpoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:bank_spo,read')->only(['index', 'show', 'showFile']);
        $this->middleware('permission:bank_spo,create')->only(['create', 'store']);
        $this->middleware('permission:bank_spo,update')->only(['edit', 'update']);
        $this->middleware('permission:bank_spo,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['file_pdf', 'unit', 'jenis_spo', 'created_at'];

            $query = BankSpo::query();

            // Filter tanggal
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('created_at', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            // Filter unit
            if ($request->unit) {
                $query->where('unit', $request->unit);
            }

            // Filter jenis SPO
            if ($request->jenis_spo) {
                $query->where('jenis_spo', $request->jenis_spo);
            }

            // Search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('jenis_spo', 'like', "%{$search}%");
                });
            }

            // Total records
            $totalRecords = $query->count();

            // Ordering
            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']];
                $orderDir = $request->order[0]['dir'];
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 50;
            $records = $query->skip($start)->take($length)->get();

            // Ambil permission di luar loop (fix error)
            $user = Auth::user();
            $canUpdate = PermissionHelper::canAccess('bank_spo', 'update');
            $canRead = PermissionHelper::canAccess('bank_spo', 'read');
            $canDelete = PermissionHelper::canAccess('bank_spo', 'delete');


            // Prepare data
            $data = [];

            foreach ($records as $item) {
                // Simpan data untuk digunakan di blade
                $data[] = [
                    'file_pdf' => $item->file_pdf,
                    'unit' => $item->unit,
                    'jenis_spo' => $item->jenis_spo,
                    'tanggal_formatted' => \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y'),
                    'id' => $item->id,
                    'can_update' => $canUpdate,
                    'can_read' => $canRead,
                    'can_delete' => $canDelete
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        }

        $bankSpo = BankSpo::latest()->get();
        return view('pages.komite-mutu.bank-spo.index', compact('bankSpo'));
    }

    public function create()
    {
        return view('pages.komite-mutu.bank-spo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|array',
            'unit.*' => 'required|string',
            'jenis_spo' => 'required|in:SPO Utama,SPO Terkait',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $units = $request->unit;
        $jenisSpo = $request->jenis_spo;
        $uploadedFile = $request->file('file_pdf');
        $originalName = $uploadedFile->getClientOriginalName();
        $filePath = "bank-spo/$originalName";

        $fileExists = Storage::disk('public')->exists($filePath);

        if ($jenisSpo === 'SPO Utama') {

            $spoUtamaExists = BankSpo::where('file_pdf', $originalName)
                ->where('jenis_spo', 'SPO Utama')
                ->exists();

            if ($spoUtamaExists) {
                return back()->withErrors([
                    'file_pdf' => 'SPO Utama dengan file ini sudah ada.'
                ]);
            }

            if (!Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->putFileAs('bank-spo', $uploadedFile, $originalName);
            }

            foreach ($units as $unit) {

                $duplikat = BankSpo::where('file_pdf', $originalName)
                    ->where('unit', $unit)
                    ->where('jenis_spo', 'SPO Utama')
                    ->exists();

                if ($duplikat) {
                    return back()->withErrors([
                        'file_pdf' => "SPO Utama untuk unit {$unit} sudah ada."
                    ]);
                }

                BankSpo::create([
                    'file_pdf'  => $originalName,
                    'file_path' => $filePath,
                    'unit'      => $unit,
                    'jenis_spo' => 'SPO Utama',
                ]);
            }
        }

        if ($jenisSpo === 'SPO Terkait') {

            foreach ($units as $unit) {

                $exists = BankSpo::where([
                    'file_pdf'  => $originalName,
                    'unit'      => $unit,
                    'jenis_spo' => 'SPO Terkait',
                ])->exists();

                if ($exists) {
                    return back()->withErrors([
                        'file_pdf' => "File SPO Terkait sudah ada untuk unit $unit."
                    ]);
                }
            }

            if (!$fileExists) {
                Storage::disk('public')->putFileAs('bank-spo', $uploadedFile, $originalName);
            }

            foreach ($units as $unit) {
                BankSpo::create([
                    'file_pdf'  => $originalName,
                    'file_path' => $filePath,
                    'unit'      => $unit,
                    'jenis_spo' => 'SPO Terkait',
                ]);
            }
        }

        return redirect()->route('komite-mutu.bank-spo.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);
        return view('pages.komite-mutu.bank-spo.detail', compact('bankSpo'));
    }

    public function showFile($id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        if (!Storage::disk('public')->exists($bankSpo->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Response::make(
            Storage::disk('public')->get($bankSpo->file_path),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $bankSpo->file_pdf . '"'
            ]
        );
    }

    public function edit(string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);
        return view('pages.komite-mutu.bank-spo.edit', compact('bankSpo'));
    }

    public function update(Request $request, string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        $request->validate([
            'unit'      => 'required|string',
            'jenis_spo' => 'required|in:SPO Utama,SPO Terkait',
            'file_pdf'  => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $unitBaru  = $request->unit;
        $jenisBaru = $request->jenis_spo;

        $filePdf  = $bankSpo->file_pdf;
        $filePath = $bankSpo->file_path;

        if ($request->hasFile('file_pdf')) {

            $uploadedFile = $request->file('file_pdf');
            $filePdf      = $uploadedFile->getClientOriginalName();
            $filePath     = 'bank-spo/' . $filePdf;

            $duplikat = BankSpo::where('id', '!=', $bankSpo->id)
                ->where('file_pdf', $filePdf)
                ->where('unit', $unitBaru)
                ->where('jenis_spo', $jenisBaru)
                ->exists();

            if ($duplikat) {
                return back()->withErrors([
                    'file_pdf' => 'File dengan unit dan jenis SPO yang sama sudah ada.'
                ]);
            }

            if (!Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->putFileAs(
                    'bank-spo',
                    $uploadedFile,
                    $filePdf
                );
            }

            $fileLamaDipakai = BankSpo::where('file_pdf', $bankSpo->file_pdf)
                ->where('id', '!=', $bankSpo->id)
                ->exists();

            if (!$fileLamaDipakai && Storage::disk('public')->exists($bankSpo->file_path)) {
                Storage::disk('public')->delete($bankSpo->file_path);
            }
        }

        $bankSpo->update([
            'unit'      => $unitBaru,
            'jenis_spo' => $jenisBaru,
            'file_pdf'  => $filePdf,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('komite-mutu.bank-spo.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        $filePdf  = $bankSpo->file_pdf;
        $filePath = $bankSpo->file_path;

        if ($bankSpo->jenis_spo === 'SPO Utama') {

            BankSpo::where('file_pdf', $filePdf)
                ->where('jenis_spo', 'SPO Terkait')
                ->delete();
        }

        $bankSpo->delete();

        $stillUsed = BankSpo::where('file_pdf', $filePdf)->exists();

        if (!$stillUsed && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return redirect()
            ->route('komite-mutu.bank-spo.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
