<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hardware;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class HardwareController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hardware,read')->only(['index', 'show']);
        $this->middleware('permission:hardware,create')->only(['create', 'store']);
        $this->middleware('permission:hardware,update')->only(['edit', 'update']);
        $this->middleware('permission:hardware,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('hardware.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = Hardware::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $hardware = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.hardware.index', compact('hardware', 'isFiltered'));
    }

    public function create()
    {
        $hardwareData = [];
        $ips = [];
        $path = public_path('assets/file/Hardware.xlsx');
        
        if (file_exists($path)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = $row[4] ?? null;
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'unit' => $row[2] ?? '',
                        'lantai' => $row[3] ?? '',
                    ];
                }
            }
        }

        return view('pages.hardware.create', compact('hardwareData', 'ips'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip'           => 'required|string|max:255',
            'nama'         => 'required|string|max:255',
            'unit'         => 'required|string|max:255',
            'lantai'       => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'checklist'    => 'nullable|array',
        ]);

        Hardware::create($validated);

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function show(Hardware $hardware)
    {
        return view('pages.hardware.detail', compact('hardware'));
    }

    public function edit(Hardware $hardware)
    {
        $hardwareData = [];
        $ips = [];
        $path = public_path('assets/file/Hardware.xlsx');
        
        if (file_exists($path)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = $row[4] ?? null;
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'unit' => $row[2] ?? '',
                        'lantai' => $row[3] ?? '',
                    ];
                }
            }
        }

        return view('pages.hardware.edit', compact('hardware', 'hardwareData', 'ips'));
    }

    public function update(Request $request, Hardware $hardware)
    {
        $validated = $request->validate([
            'ip'           => 'required|string|max:255',
            'nama'         => 'required|string|max:255',
            'unit'         => 'required|string|max:255',
            'lantai'       => 'required|string|max:255',
            'tanggal'      => 'required|date',
            'checklist'    => 'nullable|array',
        ]);

        $hardware->update($validated);

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Hardware $hardware)
    {
        $hardware->delete();

        return redirect()->route('hardware.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
