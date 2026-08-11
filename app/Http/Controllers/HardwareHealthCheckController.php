<?php

namespace App\Http\Controllers;

use App\Models\HardwareHealthCheck;
use App\Models\MasterKomputer;
use App\Models\MasterMiniPc;
use Illuminate\Http\Request;

class HardwareHealthCheckController extends Controller
{
    public $kategoriKomponen = [
        'Processor' => ['CPU Temperature', 'CPU Usage'],
        'Memory'    => ['RAM Usage'],
        'Storage'   => ['SSD/HDD Health'],
    ];

    public $statusOptions = ['Healthy', 'Warning', 'Critical'];

    public function __construct()
    {
        $this->middleware('permission:hardware,read')->only(['index', 'getData', 'getItems']);
        $this->middleware('permission:hardware,create')->only(['simpan']);
        $this->middleware('permission:hardware,update')->only(['simpan']);
        $this->middleware('permission:hardware,delete')->only(['hapus']);
    }

    public function index()
    {
        $daftarPc = [];
        MasterKomputer::orderBy('nama_pc')->get()->each(function ($pc) use (&$daftarPc) {
            $daftarPc[] = [
                'nama_pc' => $pc->nama_pc,
                'ip'      => $pc->ip,
                'unit'    => $pc->unit,
                'lantai'  => $pc->lantai,
                'jenis'   => 'Komputer',
            ];
        });
        MasterMiniPc::orderBy('nama_pc')->get()->each(function ($pc) use (&$daftarPc) {
            $daftarPc[] = [
                'nama_pc' => $pc->nama_pc,
                'ip'      => $pc->ip,
                'unit'    => '-',
                'lantai'  => $pc->lantai,
                'jenis'   => 'Mini PC & Laptop',
            ];
        });

        return view('pages.hardware.health_check', [
            'daftarPc'         => $daftarPc,
            'kategoriKomponen' => $this->kategoriKomponen,
            'statusOptions'    => $this->statusOptions,
        ]);
    }

    public function getData(Request $request)
    {
        $query = HardwareHealthCheck::query();

        if ($request->filled('cari')) {
            $cari = trim($request->cari);
            $query->where(function ($q) use ($cari) {
                $q->where('nama_pc', 'like', "%{$cari}%")
                    ->orWhere('ip', 'like', "%{$cari}%")
                    ->orWhere('unit', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('checked_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('checked_at', '<=', $request->sampai);
        }

        $checks = $query
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->get();

        $data = [];
        foreach ($checks as $check) {
            $items = $check->items ?? [];
            $counts = ['critical' => 0, 'warning' => 0, 'healthy' => 0, 'unknown' => 0];

            foreach ($items as $item) {
                $key = strtolower($item['status'] ?? '');
                if (isset($counts[$key])) $counts[$key]++;
            }

            if ($counts['critical'] > 0) {
                $overall = 'Critical';
            } elseif ($counts['warning'] > 0) {
                $overall = 'Warning';
            } elseif ($counts['healthy'] > 0) {
                $overall = 'Healthy';
            } else {
                $overall = 'Healthy';
            }

            $data[] = [
                'id'                  => $check->id,
                'nama_pc'             => $check->nama_pc,
                'ip'                  => $check->ip,
                'unit'                => $check->unit,
                'lantai'              => $check->lantai,
                'checked_at'          => $check->checked_at ? $check->checked_at->format('Y-m-d') : null,
                'checked_at_formatted' => $check->checked_at ? $check->checked_at->translatedFormat('d M Y') : '-',
                'overall'             => $overall,
                'counts'              => $counts,
                'items'               => array_values($items),
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function getItems($id)
    {
        $check = HardwareHealthCheck::findOrFail($id);

        return response()->json([
            'check' => [
                'id'         => $check->id,
                'nama_pc'    => $check->nama_pc,
                'ip'         => $check->ip,
                'unit'       => $check->unit,
                'lantai'     => $check->lantai,
                'checked_at' => $check->checked_at ? $check->checked_at->format('Y-m-d') : null,
                'jenis'      => ($check->unit !== null && $check->unit !== '-' && $check->unit !== '') ? 'Komputer' : 'Mini PC & Laptop',
            ],
            'items' => array_values($check->items ?? []),
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id'                  => 'nullable|integer',
            'nama_pc'             => 'required|string|max:255',
            'checked_at'          => 'nullable|date',
            'rows'                => 'array',
            'rows.*.category'     => 'required|string',
            'rows.*.component'    => 'required|string',
            'rows.*.status'       => 'required|string',
            'rows.*.value'        => 'nullable|numeric',
        ]);

        $items = [];
        foreach (($request->rows ?? []) as $row) {
            $component = trim($row['component'] ?? '');
            $status    = in_array($row['status'] ?? '', $this->statusOptions) ? $row['status'] : 'Healthy';
            $value     = trim($row['value'] ?? '');
            $notes     = trim($row['notes'] ?? '');

            if ($component === '') continue;
            if ($status === 'Healthy' && $value === '' && $notes === '') continue;

            $items[] = [
                'category'  => $row['category'],
                'component' => $component,
                'value'     => $value !== '' ? $value : null,
                'status'    => $status,
                'notes'     => $notes !== '' ? $notes : null,
            ];
        }

        $dataHeader = [
            'nama_pc'    => $request->nama_pc,
            'ip'         => $request->ip ?: null,
            'unit'       => $request->unit ?: null,
            'lantai'     => $request->lantai ?: null,
            'checked_at' => $request->checked_at ?: now(),
            'items'      => $items,
        ];

        if ($request->filled('id')) {
            $check = HardwareHealthCheck::findOrFail($request->id);
            $check->update($dataHeader);
        } else {
            $check = HardwareHealthCheck::create($dataHeader);
        }

        return response()->json(['success' => true, 'id' => $check->id]);
    }

    public function hapus($id)
    {
        $check = HardwareHealthCheck::findOrFail($id);
        $check->delete();

        return response()->json(['success' => true]);
    }
}