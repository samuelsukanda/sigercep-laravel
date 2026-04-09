<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankSpo;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        // ================= AJAX DATATABLE =================
        if ($request->ajax()) {

            $columns = ['file_pdf', 'unit', 'jenis_spo', 'created_at'];

            $query = BankSpo::query();

            // ================= FILTER =================

            // 🔥 FILTER TANGGAL (FORMAT d-m-Y)
            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                } catch (\Exception $e) {
                    // skip kalau format salah
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                } catch (\Exception $e) {
                    // skip kalau format salah
                }
            }

            // FILTER UNIT
            if ($request->filled('unit')) {
                $query->where('unit', $request->unit);
            }

            // FILTER JENIS SPO
            if ($request->filled('jenis_spo')) {
                $query->where('jenis_spo', $request->jenis_spo);
            }

            // ================= SEARCH =================
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('file_pdf', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('jenis_spo', 'like', "%{$search}%");
                });
            }

            // ================= TOTAL =================
            $recordsTotal = BankSpo::count();
            $recordsFiltered = $query->count();

            // ================= ORDER =================
            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']];
                $orderDir = $request->order[0]['dir'];
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // ================= PAGINATION =================
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            // ================= PERMISSION =================
            $canUpdate = PermissionHelper::canAccess('bank_spo', 'update');
            $canRead = PermissionHelper::canAccess('bank_spo', 'read');
            $canDelete = PermissionHelper::canAccess('bank_spo', 'delete');

            // ================= FORMAT DATA =================
            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'file_pdf' => $item->file_pdf,
                    'unit' => $item->unit,
                    'jenis_spo' => $item->jenis_spo,
                    'tanggal_formatted' => Carbon::parse($item->created_at)->translatedFormat('d F Y'),
                    'can_update' => $canUpdate,
                    'can_read' => $canRead,
                    'can_delete' => $canDelete,
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }

        // ================= VIEW =================
        return view('pages.komite-mutu.bank-spo.index');
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

        if (!Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->putFileAs('bank-spo', $uploadedFile, $originalName);
        }

        foreach ($units as $unit) {
            BankSpo::create([
                'file_pdf'  => $originalName,
                'file_path' => $filePath,
                'unit'      => $unit,
                'jenis_spo' => $jenisSpo,
            ]);
        }

        return redirect()->route('komite-mutu.bank-spo.index')
            ->with('success', 'Data berhasil disimpan.');
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

    public function destroy($id)
    {
        $bankSpo = BankSpo::findOrFail($id);

        $filePath = $bankSpo->file_path;

        $bankSpo->delete();

        if (!BankSpo::where('file_path', $filePath)->exists()) {
            Storage::disk('public')->delete($filePath);
        }

        return response()->json(['success' => true]);
    }
}
