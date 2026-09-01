<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hardware;
use App\Models\MasterKomputer;
use App\Models\MasterMiniPc;
use App\Helpers\PermissionHelper;
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
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'lantai', 'tanggal'];

            $query = Hardware::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->whereDate('tanggal', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->whereDate('tanggal', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%")
                        ->orWhere('lantai', 'like', "%{$search}%")
                        ->orWhere('ip', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Hardware::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']];
                $orderDir = $request->order[0]['dir'];
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('hardware', 'update');
            $canRead = PermissionHelper::canAccess('hardware', 'read');
            $canDelete = PermissionHelper::canAccess('hardware', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'lantai' => $item->lantai,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->tanggal)->translatedFormat('d F Y') . '</span>
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
                'data' => $data
            ]);
        }

        $listLantaiKomputer = MasterKomputer::whereNotNull('lantai')->distinct()->pluck('lantai')->toArray();
        $listLantaiMiniPc = MasterMiniPc::whereNotNull('lantai')->distinct()->pluck('lantai')->toArray();
        $listLantai = array_unique(array_merge($listLantaiKomputer, $listLantaiMiniPc));
        sort($listLantai);

        return view('pages.hardware.index', compact('listLantai'));
    }

    public function report(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $selectedLantai = $request->input('lantai');

        $listLantai = MasterKomputer::whereNotNull('lantai')
            ->distinct()
            ->orderBy('lantai')
            ->pluck('lantai')
            ->toArray();

        $query = MasterKomputer::query();
        if ($selectedLantai) {
            $query->where('lantai', $selectedLantai);
        }
        $pcMasters = $query->get();

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

        $listLantai = MasterMiniPc::whereNotNull('lantai')
            ->distinct()
            ->orderBy('lantai')
            ->pluck('lantai')
            ->toArray();

        $query = MasterMiniPc::query();
        if ($selectedLantai) {
            $query->where('lantai', $selectedLantai);
        }
        $pcMasters = $query->get();

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

        // Ambil dari master_komputer
        MasterKomputer::all()->each(function ($pc) use (&$hardwareData, &$ips) {
            $ips[] = $pc->ip;
            $hardwareData[$pc->ip] = [
                'nama_pc'  => $pc->nama_pc,
                'jenis_pc' => $pc->jenis_pc,
                'unit'     => $pc->unit,
                'lantai'   => $pc->lantai,
            ];
        });

        // Ambil dari master_mini_pc
        MasterMiniPc::all()->each(function ($pc) use (&$hardwareData, &$ips) {
            $ips[] = $pc->ip;
            $hardwareData[$pc->ip] = [
                'nama_pc'  => $pc->nama_pc,
                'jenis_pc' => $pc->jenis_pc,
                'unit'     => '-',
                'lantai'   => $pc->lantai,
            ];
        });

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

        return redirect(route('hardware.index') . '?saved=1')
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

        // Ambil dari master_komputer
        MasterKomputer::all()->each(function ($pc) use (&$hardwareData, &$ips) {
            $ips[] = $pc->ip;
            $hardwareData[$pc->ip] = [
                'nama_pc'  => $pc->nama_pc,
                'jenis_pc' => $pc->jenis_pc,
                'unit'     => $pc->unit,
                'lantai'   => $pc->lantai,
            ];
        });

        // Ambil dari master_mini_pc
        MasterMiniPc::all()->each(function ($pc) use (&$hardwareData, &$ips) {
            $ips[] = $pc->ip;
            $hardwareData[$pc->ip] = [
                'nama_pc'  => $pc->nama_pc,
                'jenis_pc' => $pc->jenis_pc,
                'unit'     => '-',
                'lantai'   => $pc->lantai,
            ];
        });

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

        return redirect(route('hardware.index') . '?updated=1')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Hardware $hardware)
    {
        $hardware->delete();

        return redirect(route('hardware.index') . '?deleted=1')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function showDevicePrinter($ip)
    {
        $pcData = null;

        $cleanStr = function ($str) {
            if (empty($str)) return '';
            $str = str_replace("\xC2\xA0", ' ', $str);
            $str = preg_replace('/<br\s*\/?>/i', "\n", $str);
            return trim(preg_replace('/^\s+/u', '', $str));
        };

        // Cari di master_komputer
        $master = MasterKomputer::where('ip', $ip)->first();
        if ($master) {
            $pcData = (object)[
                'ip'       => $master->ip,
                'nama_pc'  => $master->nama_pc,
                'jenis_pc' => $master->jenis_pc,
                'unit'     => $master->unit,
                'lantai'   => $master->lantai,
                'ram'      => $cleanStr($master->ram ?? ''),
                'cpu'      => $cleanStr($master->cpu ?? ''),
            ];
        }

        // Jika tidak ditemukan, cari di master_mini_pc
        if (!$pcData) {
            $masterMini = MasterMiniPc::where('ip', $ip)->first();
            if ($masterMini) {
                $pcData = (object)[
                    'ip'       => $masterMini->ip,
                    'nama_pc'  => $masterMini->nama_pc,
                    'jenis_pc' => $masterMini->jenis_pc,
                    'unit'     => '',
                    'lantai'   => $masterMini->lantai,
                    'ram'      => $cleanStr($masterMini->ram ?? ''),
                    'cpu'      => $cleanStr($masterMini->cpu ?? ''),
                ];
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

        return redirect(route('hardware.device-printer.show', $ip) . '?saved=1')
            ->with('success', 'Device/Printer berhasil ditambahkan.');
    }

    public function updateDevicePrinter(Request $request, $id)
    {
        $devicePrinter = \App\Models\DevicePrinter::findOrFail($id);

        $validated = $request->validate([
            'nama_perangkat' => 'required|string|max:255',
            'jenis'          => 'required|string|max:255',
            'merk_type'      => 'nullable|string|max:255',
            'kondisi'        => 'required|string|max:255',
            'keterangan'     => 'nullable|string',
        ]);

        $devicePrinter->update($validated);

        return redirect(route('hardware.device-printer.show', $devicePrinter->ip_pc) . '?updated=1')
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

        return redirect(route('hardware.device-printer.show', $ip) . '?deleted=1')
            ->with('success', 'Device/Printer berhasil dihapus.');
    }

    // ── Master Komputer ──────────────────────────────────────────────

    public function editMasterKomputer($id)
    {
        $master = MasterKomputer::findOrFail($id);
        return view('pages.hardware.master_komputer_edit', compact('master'));
    }

    public function updateMasterKomputer(Request $request, $id)
    {
        $master = MasterKomputer::findOrFail($id);

        $validated = $request->validate([
            'nama_pc'  => 'required|string|max:255',
            'jenis_pc' => 'nullable|string|max:255',
            'unit'     => 'nullable|string|max:255',
            'lantai'   => 'nullable|string|max:255',
            'ip'       => 'required|string|max:255|unique:master_komputer,ip,' . $id,
            'ram'      => 'nullable|string|max:255',
            'cpu'      => 'nullable|string|max:255',
        ]);

        $master->update($validated);

        return redirect()->route('hardware.reports')
            ->with('success', 'Data master komputer berhasil diperbarui.');
    }

    // ── Master Mini PC ───────────────────────────────────────────────

    public function editMasterMiniPc($id)
    {
        $master = MasterMiniPc::findOrFail($id);
        return view('pages.hardware.master_mini_pc_edit', compact('master'));
    }

    public function updateMasterMiniPc(Request $request, $id)
    {
        $master = MasterMiniPc::findOrFail($id);

        $validated = $request->validate([
            'nama_pc'  => 'required|string|max:255',
            'jenis_pc' => 'nullable|string|max:255',
            'lantai'   => 'nullable|string|max:255',
            'ip'       => 'required|string|max:255|unique:master_mini_pc,ip,' . $id,
            'ram'      => 'nullable|string|max:255',
            'cpu'      => 'nullable|string|max:255',
        ]);

        $master->update($validated);

        return redirect()->route('hardware.reports.minipc')
            ->with('success', 'Data master Mini PC berhasil diperbarui.');
    }

    // ── Generate Otomatis Ceklis Hardware ────────────────────────────
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['required', 'date_format:d-m-Y'],
            'lantai'  => 'required|array',
            'lantai.*' => 'string',
        ]);

        $carbonDate = Carbon::createFromFormat('d-m-Y', $validated['tanggal']);
        $tanggal = $carbonDate->format('Y-m-d');
        $bulan   = $carbonDate->format('m');
        $tahun   = $carbonDate->format('Y');

        $lantai = $validated['lantai'];

        $komputers = MasterKomputer::whereIn('lantai', $lantai)->get();

        $count = 0;

        $checklistItems = [
            'Wallpaper backround RS',
            'Password admin dan user terkontrol',
            'Screen saver jalan',
            'Aplikasi remote VNC berjalan',
            'Bersihkan komputer dari software yang tidak diijinkan',
            'Cek kapasitas hardisk sistem operasi C',
            'Printer dan hardware pendukung berfungsi',
            'Cleaning CPU & Cek Pengkabelan',
            'Hapus cache temp dan cache browser',
            'Akses Flashdisk terkontrol',
        ];

        $defaultChecklist = [];
        foreach ($checklistItems as $item) {
            $defaultChecklist[$item] = [
                'status'     => 1,
                'keterangan' => null,
            ];
        }

        foreach ($komputers as $pc) {
            // Cek apakah sudah ada untuk IP ini di bulan & tahun ini
            $exists = Hardware::where('ip', $pc->ip)
                ->whereYear('tanggal', $tahun)
                ->whereMonth('tanggal', $bulan)
                ->exists();

            if (!$exists) {
                Hardware::create([
                    'ip'        => $pc->ip,
                    'nama'      => $pc->nama_pc,
                    'unit'      => $pc->unit ?? '-',
                    'lantai'    => $pc->lantai,
                    'tanggal'   => $tanggal,
                    'checklist' => $defaultChecklist,
                ]);
                $count++;
            }
        }

        $lantaiList = implode(', ', $lantai);
        return redirect()->route('hardware.index')
            ->with('success', "Berhasil menggenerate $count data ceklis hardware untuk lantai $lantaiList.");
    }
}
