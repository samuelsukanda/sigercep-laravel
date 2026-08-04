<?php

namespace App\Http\Controllers;

use App\Models\ReservasiKendaraan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PermissionHelper;

class ReservasiKendaraanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:reservasi_kendaraan,read')->only(['index', 'show']);
        $this->middleware('permission:reservasi_kendaraan,create')->only(['create', 'store']);
        $this->middleware('permission:reservasi_kendaraan,update')->only(['edit', 'update']);
        $this->middleware('permission:reservasi_kendaraan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'jam_berangkat', 'jam_pulang', 'tanggal', 'jenis_kendaraan', 'tempat_tujuan'];

            $query = ReservasiKendaraan::query();

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
                        ->orWhere('jenis_kendaraan', 'like', "%{$search}%")
                        ->orWhere('tempat_tujuan', 'like', "%{$search}%")
                        ->orWhere('keperluan', 'like', "%{$search}%");
                });
            }

            $recordsTotal = ReservasiKendaraan::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal', 'desc')->orderBy('jam_berangkat', 'asc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('reservasi_kendaraan', 'update');
            $canRead = PermissionHelper::canAccess('reservasi_kendaraan', 'read');
            $canDelete = PermissionHelper::canAccess('reservasi_kendaraan', 'delete');

            $data = [];

            foreach ($records as $item) {
                $jamBerangkat = '-';
                if (!empty($item->jam_berangkat)) {
                    try {
                        $jamBerangkat = Carbon::createFromFormat('H:i:s', $item->jam_berangkat)->format('H:i') . ' WIB';
                    } catch (\Exception $e) {
                        $jamBerangkat = $item->jam_berangkat;
                    }
                }
                $jamPulang = '-';
                if (!empty($item->jam_pulang)) {
                    try {
                        $jamPulang = Carbon::createFromFormat('H:i:s', $item->jam_pulang)->format('H:i') . ' WIB';
                    } catch (\Exception $e) {
                        $jamPulang = $item->jam_pulang;
                    }
                }

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'jam_berangkat' => $jamBerangkat,
                    'jam_pulang' => $jamPulang,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->tanggal)->translatedFormat('d F Y') . '</span>
                    </div>
                ',
                    'jenis_kendaraan' => $item->jenis_kendaraan,
                    'tempat_tujuan' => $item->tempat_tujuan,
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

        return view('pages.reservasi.kendaraan.index');
    }

    public function create()
    {
        return view('pages.reservasi.kendaraan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tempat_tujuan' => 'required|string|max:100',
            'keperluan' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_berangkat',
            'tanggal' => 'required|date',
            'jenis_kendaraan' => 'required|string|max:50',
            'jumlah_penumpang' => 'required|string|max:50',
            'waktu_tempuh' => 'required|string|max:50',
            'jarak_tempuh' => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:50',
        ], [
            'jam_pulang.after' => 'Jam Pulang harus lebih besar dari Jam Berangkat.',
        ]);

        $isOverlap = ReservasiKendaraan::where('jenis_kendaraan', $validated['jenis_kendaraan'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_berangkat', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_berangkat', '<=', $validated['jam_berangkat'])
                            ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        ReservasiKendaraan::create($validated);

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        return view('pages.reservasi.kendaraan.edit', compact('reservasi'));
    }

    public function show(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        return view('pages.reservasi.kendaraan.detail', compact('reservasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'tempat_tujuan' => 'required|string|max:100',
            'keperluan' => 'required|string|max:100',
            'jam_berangkat' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_berangkat',
            'tanggal' => 'required|date',
            'jenis_kendaraan' => 'required|string|max:50',
            'jumlah_penumpang' => 'required|string|max:50',
            'waktu_tempuh' => 'required|string|max:50',
            'jarak_tempuh' => 'required|string|max:50',
            'jenis_layanan' => 'required|string|max:50',
        ], [
            'jam_pulang.after' => 'Jam Pulang harus lebih besar dari Jam Berangkat.',
        ]);

        $isOverlap = ReservasiKendaraan::where('id', '<>', $id)
            ->where('jenis_kendaraan', $validated['jenis_kendaraan'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_berangkat', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhereBetween('jam_pulang', [$validated['jam_berangkat'], $validated['jam_pulang']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_berangkat', '<=', $validated['jam_berangkat'])
                            ->where('jam_pulang', '>=', $validated['jam_pulang']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        $reservasi = ReservasiKendaraan::findOrFail($id);
        $reservasi->update($validated);

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $reservasi = ReservasiKendaraan::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('reservasi.kendaraan.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
