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
        $this->middleware('permission:hardware,read')->only(['index', 'show', 'report', 'reportMiniPc', 'showDevicePrinter']);
        $this->middleware('permission:hardware,create')->only(['create', 'store', 'storeDevicePrinter']);
        $this->middleware('permission:hardware,update')->only(['edit', 'update', 'updateDevicePrinter']);
        $this->middleware('permission:hardware,delete')->only(['destroy', 'destroyDevicePrinter']);
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

    public function report(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $selectedLantai = $request->input('lantai');

        $pcMasters = [];
        $listLantai = [];
        $path = public_path('assets/file/Hardware.xlsx');
        
        if (file_exists($path)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = $row[5] ?? null;
                $lantai = $row[4] ?? '';
                if (!empty($ip)) {
                    if (!empty($lantai) && !in_array($lantai, $listLantai)) {
                        $listLantai[] = $lantai;
                    }

                    if ($selectedLantai && $lantai !== $selectedLantai) {
                        continue;
                    }

                    $pcMasters[] = (object)[
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => $row[3] ?? '',
                        'lantai' => $lantai,
                        'ip' => $ip,
                    ];
                }
            }
        }
        
        sort($listLantai);

        $hardwareChecks = Hardware::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy('ip');

        return view('pages.hardware.reports', compact('pcMasters', 'hardwareChecks', 'bulan', 'tahun', 'listLantai', 'selectedLantai'));
    }

    public function reportMiniPc(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $selectedLantai = $request->input('lantai');

        $pcMasters = [];
        $listLantai = [];
        $path = public_path('assets/file/Mini PC.xlsx');
        
        if (file_exists($path)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = str_replace(',', '.', $row[4] ?? ''); // IP index shifted from 3 to 4
                $lantai = $row[3] ?? ''; // Lantai index shifted from 2 to 3
                if (!empty($ip)) {
                    if (!empty($lantai) && !in_array($lantai, $listLantai)) {
                        $listLantai[] = $lantai;
                    }

                    if ($selectedLantai && $lantai !== $selectedLantai) {
                        continue;
                    }

                    $pcMasters[] = (object)[
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => '',
                        'lantai' => $lantai,
                        'ip' => $ip,
                    ];
                }
            }
        }
        
        sort($listLantai);

        $hardwareChecks = Hardware::whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get()
            ->keyBy('ip');

        return view('pages.hardware.reports_minipc', compact('pcMasters', 'hardwareChecks', 'bulan', 'tahun', 'listLantai', 'selectedLantai'));
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
                
                $ip = $row[5] ?? null;
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => $row[3] ?? '',
                        'lantai' => $row[4] ?? '',
                    ];
                }
            }
        }

        $pathMiniPc = public_path('assets/file/Mini PC.xlsx');
        if (file_exists($pathMiniPc)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($pathMiniPc);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = str_replace(',', '.', $row[4] ?? '');
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => '-',
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
                
                $ip = $row[5] ?? null;
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => $row[3] ?? '',
                        'lantai' => $row[4] ?? '',
                    ];
                }
            }
        }

        $pathMiniPc = public_path('assets/file/Mini PC.xlsx');
        if (file_exists($pathMiniPc)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($pathMiniPc);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $ip = str_replace(',', '.', $row[4] ?? '');
                if (!empty($ip)) {
                    $ips[] = $ip;
                    $hardwareData[$ip] = [
                        'nama_pc' => $row[1] ?? '',
                        'jenis_pc' => $row[2] ?? '',
                        'unit' => '-',
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

    public function showDevicePrinter($ip)
    {
        $pcData = null;
        $path = public_path('assets/file/Hardware.xlsx');
        
        if (file_exists($path)) {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Skip header
                
                $rowIp = $row[5] ?? null;
                if ($rowIp == $ip) {
                    // RAM = kolom 6, CPU = kolom 7, gabungkan dengan newline
                    $spesParts = array_filter([
                        trim($row[6] ?? ''),
                        trim($row[7] ?? ''),
                    ]);
                    $pcData = (object)[
                        'ip'          => $rowIp,
                        'nama_pc'     => $row[1] ?? '',
                        'jenis_pc'    => $row[2] ?? '',
                        'unit'        => $row[3] ?? '',
                        'lantai'      => $row[4] ?? '',
                        'spesifikasi' => implode("\n", $spesParts),
                    ];
                    break;
                }
            }
        }

        // Jika tidak ditemukan di Hardware, cari di Mini PC
        if (!$pcData) {
            $pathMiniPc = public_path('assets/file/Mini PC.xlsx');
            if (file_exists($pathMiniPc)) {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($pathMiniPc);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // Skip header
                    
                    $rowIp = str_replace(',', '.', $row[4] ?? '');
                    if ($rowIp == $ip) {
                        // Mini PC: RAM = kolom 5, CPU = kolom 6 (sesuaikan jika kolom berbeda)
                        $spesParts = array_filter([
                            trim($row[5] ?? ''),
                            trim($row[6] ?? ''),
                        ]);
                        $pcData = (object)[
                            'ip'          => $rowIp,
                            'nama_pc'     => $row[1] ?? '',
                            'jenis_pc'    => $row[2] ?? '',
                            'unit'        => '',
                            'lantai'      => $row[3] ?? '',
                            'spesifikasi' => implode("\n", $spesParts),
                        ];
                        break;
                    }
                }
            }
        }

        if (!$pcData) {
            return redirect()->back()->with('error', 'Data PC tidak ditemukan.');
        }

        $devicePrinters = \App\Models\DevicePrinter::where('ip_pc', $ip)->get();

        return view('pages.hardware.device_printer', compact('pcData', 'devicePrinters'));
    }

    public function storeDevicePrinter(Request $request, $ip)
    {
        $validated = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'jenis'          => 'required|string|max:255',
            'merk_type'      => 'nullable|string|max:255',
            'kondisi'        => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
            'foto'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['ip_pc'] = $ip;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'device-' . now()->format('YmdHis') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/device_printers', $filename, 'public');
            $validated['foto'] = $path;
        }

        \App\Models\DevicePrinter::create($validated);

        return redirect()->route('hardware.device-printer.show', $ip)
            ->with('success', 'Device/Printer berhasil ditambahkan.');
    }

    public function updateDevicePrinter(Request $request, $id)
    {
        $devicePrinter = \App\Models\DevicePrinter::findOrFail($id);

        $validated = $request->validate([
            'kondisi'    => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $devicePrinter->update($validated);

        return redirect()->route('hardware.device-printer.show', $devicePrinter->ip_pc)
            ->with('success', 'Data device/printer berhasil diperbarui.');
    }

    public function destroyDevicePrinter($id)
    {
        $devicePrinter = \App\Models\DevicePrinter::findOrFail($id);
        $ip = $devicePrinter->ip_pc;

        if ($devicePrinter->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($devicePrinter->foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($devicePrinter->foto);
        }

        $devicePrinter->delete();

        return redirect()->route('hardware.device-printer.show', $ip)
            ->with('success', 'Device/Printer berhasil dihapus.');
    }
}
