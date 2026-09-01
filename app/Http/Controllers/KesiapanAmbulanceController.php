<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesiapanAmbulance;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class KesiapanAmbulanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:kesiapan_ambulance,read')->only(['index', 'show']);
        $this->middleware('permission:kesiapan_ambulance,create')->only(['create', 'store']);
        $this->middleware('permission:kesiapan_ambulance,update')->only(['edit', 'update']);
        $this->middleware('permission:kesiapan_ambulance,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['mobil_ambulance', 'tanggal', 'perawat', 'kondisi_mobil', 'kondisi_driver'];

            $query = KesiapanAmbulance::query();

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
                    $q->where('mobil_ambulance', 'like', "%{$search}%")
                        ->orWhere('perawat', 'like', "%{$search}%")
                        ->orWhere('kondisi_mobil', 'like', "%{$search}%")
                        ->orWhere('kondisi_driver', 'like', "%{$search}%");
                });
            }

            $recordsTotal = KesiapanAmbulance::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('kesiapan_ambulance', 'update');
            $canRead = PermissionHelper::canAccess('kesiapan_ambulance', 'read');
            $canDelete = PermissionHelper::canAccess('kesiapan_ambulance', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'mobil_ambulance' => $item->mobil_ambulance,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'perawat' => $item->perawat,
                    'kondisi_mobil' => $item->kondisi_mobil,
                    'kondisi_driver' => $item->kondisi_driver,
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

        return view('pages.kesiapan-ambulance.index');
    }

    public function create()
    {
        return view('pages.kesiapan-ambulance.create');
    }

    public function store(Request $request)
    {
        $fieldsWithOtherOption = [
            'kondisi_mobil',
            'kondisi_driver',
            'oksigen',
            'regulator_oksigen',
            'kebersihan',
            'monitor_pasien',
            'aed',
            'suction',
            'ventilator',
            'bed_pasien',
            'linen',
            'obat',
            'inverter',
        ];

        $rules = [
            'mobil_ambulance' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'perawat' => 'required|string|max:50',
        ];

        foreach ($fieldsWithOtherOption as $field) {
            $rules[$field] = 'required|string|max:50';
            $rules["{$field}_input"] = "nullable|string|max:50";
        }

        $data = $request->validate($rules);

        foreach ($fieldsWithOtherOption as $field) {
            if ($request->input($field) === 'Other') {
                $data[$field] = $request->input("{$field}_input");
            }

            unset($data["{$field}_input"]);
        }

        KesiapanAmbulance::create($data);

        return redirect(route('kesiapan-ambulance.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $ambulance = KesiapanAmbulance::findOrFail($id);
        return view('pages.kesiapan-ambulance.detail', compact('ambulance'));
    }

    public function edit(string $id)
    {
        $ambulance = KesiapanAmbulance::findOrFail($id);
        return view('pages.kesiapan-ambulance.edit', compact('ambulance'));
    }

    public function update(Request $request, string $id)
    {
        $ambulance = KesiapanAmbulance::findOrFail($id);

        $fieldsWithOtherOption = [
            'kondisi_mobil',
            'kondisi_driver',
            'oksigen',
            'regulator_oksigen',
            'kebersihan',
            'monitor_pasien',
            'aed',
            'suction',
            'ventilator',
            'bed_pasien',
            'linen',
            'obat',
            'inverter',
        ];

        $rules = [
            'mobil_ambulance' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'perawat' => 'required|string|max:50',
        ];

        foreach ($fieldsWithOtherOption as $field) {
            $rules[$field] = 'required|string|max:50';
            $rules["{$field}_input"] = "nullable|string|max:50";
        }

        $data = $request->validate($rules);

        foreach ($fieldsWithOtherOption as $field) {
            if ($request->input($field) === 'Other') {
                $data[$field] = $request->input("{$field}_input");
            }

            unset($data["{$field}_input"]);
        }

        $ambulance->update($data);

        return redirect(route('kesiapan-ambulance.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ambulance = KesiapanAmbulance::findOrFail($id);
        $ambulance->delete();

        return redirect(route('kesiapan-ambulance.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
