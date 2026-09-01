<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PelaporanIkp;
use Carbon\Carbon;
use App\Helpers\PermissionHelper;

class PelaporanIkpController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pelaporan_ikp,read')->only(['index', 'show']);
        $this->middleware('permission:pelaporan_ikp,create')->only(['create', 'store']);
        $this->middleware('permission:pelaporan_ikp,update')->only(['edit', 'update']);
        $this->middleware('permission:pelaporan_ikp,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = [
                'nama',
                'no_rm',
                'tanggal_kejadian',
                'jenis_kelamin',
                'kelompok_umur',
                'jenis_insiden',
                'grading_risiko',
            ];

            $query = PelaporanIkp::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->whereDate('tanggal_kejadian', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->whereDate('tanggal_kejadian', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('no_rm', 'like', "%{$search}%")
                        ->orWhere('jenis_kelamin', 'like', "%{$search}%")
                        ->orWhere('kelompok_umur', 'like', "%{$search}%")
                        ->orWhere('jenis_insiden', 'like', "%{$search}%")
                        ->orWhere('grading_risiko', 'like', "%{$search}%");
                });
            }

            $recordsTotal = PelaporanIkp::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal_kejadian';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal_kejadian', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('pelaporan_ikp', 'update');
            $canRead = PermissionHelper::canAccess('pelaporan_ikp', 'read');
            $canDelete = PermissionHelper::canAccess('pelaporan_ikp', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'no_rm' => $item->no_rm,
                    'tanggal_kejadian_timestamp' => Carbon::parse($item->tanggal_kejadian)->timestamp,
                    'tanggal_kejadian_formatted' => Carbon::parse($item->tanggal_kejadian)->translatedFormat('d F Y'),
                    'jenis_kelamin' => $item->jenis_kelamin,
                    'kelompok_umur' => $item->kelompok_umur,
                    'jenis_insiden' => $item->jenis_insiden,
                    'grading_risiko' => $item->grading_risiko,
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

        return view('pages.komite-mutu.pelaporan-ikp.index');
    }

    public function create()
    {
        return view('pages.komite-mutu.pelaporan-ikp.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_rm' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'kelompok_umur' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|max:20',
            'penanggung_jawab' => 'required|string|max:255',
            'tanggal_masuk_rs' => 'required|date',
            'rincian_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|string|max:50',
            'insiden' => 'required|string|max:255',
            'kronologis_kejadian' => 'required|string',
            'jenis_kejadian' => 'required|string|max:255',
            'orang_pelapor' => 'required|string|max:255',
            'jenis_insiden' => 'required|string|max:255',
            'insiden_pasien' => 'required|string|max:255',
            'lokasi_insiden' => 'required|string|max:255',
            'jenis_spesialisasi_pasien' => 'required|string|max:255',
            'unit_terkait' => 'required|string|max:255',
            'akibat_insiden' => 'required|string',
            'tindakan_yang_dilakukan' => 'required|string|max:1000',
            'tindakan_dilakukan_oleh' => 'required|string|max:255',
            'kejadian_serupa' => 'required|string|max:255',
            'grading_risiko' => 'required|string|max:100',
        ]);

        PelaporanIkp::create($validated);

        return redirect(route('komite-mutu.pelaporan-ikp.index') . '?saved=1')->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        return view('pages.komite-mutu.pelaporan-ikp.detail', compact('pelaporanIkp'));
    }

    public function edit(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        return view('pages.komite-mutu.pelaporan-ikp.edit', compact('pelaporanIkp'));
    }

    public function update(Request $request, string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_rm' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'kelompok_umur' => 'required|string|max:50',
            'jenis_kelamin' => 'required|string|max:20',
            'penanggung_jawab' => 'required|string|max:255',
            'tanggal_masuk_rs' => 'required|date',
            'rincian_kejadian' => 'required|string',
            'tanggal_kejadian' => 'required|date',
            'waktu_kejadian' => 'required|string|max:50',
            'insiden' => 'nullable|string|max:255',
            'kronologis_kejadian' => 'nullable|string',
            'jenis_kejadian' => 'nullable|string|max:255',
            'orang_pelapor' => 'nullable|string|max:255',
            'jenis_insiden' => 'nullable|string|max:255',
            'insiden_pasien' => 'nullable|string|max:255',
            'lokasi_insiden' => 'nullable|string|max:255',
            'jenis_spesialisasi_pasien' => 'nullable|string|max:255',
            'unit_terkait' => 'nullable|string|max:255',
            'akibat_insiden' => 'nullable|string',
            'tindakan_yang_dilakukan' => 'nullable|string|max:1000',
            'tindakan_dilakukan_oleh' => 'nullable|string|max:255',
            'kejadian_serupa' => 'nullable|string|max:255',
            'grading_risiko' => 'nullable|string|max:100',
        ]);

        $pelaporanIkp->update($validated);

        return redirect(route('komite-mutu.pelaporan-ikp.index') . '?updated=1')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pelaporanIkp = PelaporanIkp::findOrFail($id);
        $pelaporanIkp->delete();

        return redirect(route('komite-mutu.pelaporan-ikp.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}
