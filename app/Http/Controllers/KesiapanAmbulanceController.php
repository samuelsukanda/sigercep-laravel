<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KesiapanAmbulance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

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
            return redirect()->route('kesiapan-ambulance.index')
                ->withErrors($validator)
                ->withInput();
        }

        $query = KesiapanAmbulance::query();

        if ($request->filled('periode_dari')) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('periode_sampai')) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
            $query->whereDate('tanggal', '<=', $endDate);
        }

        $ambulance = $query->orderBy('tanggal', 'desc')->get();

        return view('pages.kesiapan-ambulance.index', compact('ambulance', 'isFiltered'));
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

        return redirect()->route('kesiapan-ambulance.index')->with('success', 'Data berhasil disimpan.');
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

        return redirect()->route('kesiapan-ambulance.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $ambulance = KesiapanAmbulance::findOrFail($id);
        $ambulance->delete();

        return redirect()->route('kesiapan-ambulance.index')->with('success', 'Data berhasil dihapus.');
    }
}
