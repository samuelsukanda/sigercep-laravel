<?php

namespace App\Http\Controllers;

use App\Models\ReservasiRuangan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PermissionHelper;

class ReservasiRuanganController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:reservasi_ruangan,read')->only(['index', 'show']);
        $this->middleware('permission:reservasi_ruangan,create')->only(['create', 'store']);
        $this->middleware('permission:reservasi_ruangan,update')->only(['edit', 'update']);
        $this->middleware('permission:reservasi_ruangan,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['nama', 'unit', 'jam_mulai', 'jam_selesai', 'tanggal', 'ruang', 'approval'];

            $query = ReservasiRuangan::query();

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
                        ->orWhere('ruang', 'like', "%{$search}%")
                        ->orWhere('approval', 'like', "%{$search}%");
                });
            }

            $recordsTotal = ReservasiRuangan::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'asc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('reservasi_ruangan', 'update');
            $canRead = PermissionHelper::canAccess('reservasi_ruangan', 'read');
            $canDelete = PermissionHelper::canAccess('reservasi_ruangan', 'delete');

            $data = [];

            foreach ($records as $item) {
                $jamMulai = '-';
                if (!empty($item->jam_mulai)) {
                    try {
                        $jamMulai = Carbon::createFromFormat('H:i:s', $item->jam_mulai)->format('H:i') . ' WIB';
                    } catch (\Exception $e) {
                        $jamMulai = $item->jam_mulai;
                    }
                }
                $jamSelesai = '-';
                if (!empty($item->jam_selesai)) {
                    try {
                        $jamSelesai = Carbon::createFromFormat('H:i:s', $item->jam_selesai)->format('H:i') . ' WIB';
                    } catch (\Exception $e) {
                        $jamSelesai = $item->jam_selesai;
                    }
                }

                $approval = $item->approval ?? 'Pending';
                $approvalHtml = view('components.badge.status-approval-badge', ['status' => $approval])->render();

                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'unit' => $item->unit,
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->tanggal)->translatedFormat('d F Y') . '</span>
                    </div>
                ',
                    'ruang' => $item->ruang,
                    'approval_badge' => $approvalHtml,
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

        return view('pages.reservasi.ruangan.index');
    }

    public function create()
    {
        return view('pages.reservasi.ruangan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'required|date',
            'ruang' => 'required|string|max:50',
            'approval' => 'nullable|in:Pending,Approved,Rejected',
        ], [
            'jam_selesai.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.',
        ]);

        $isOverlap = ReservasiRuangan::where('ruang', $validated['ruang'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        ReservasiRuangan::create($validated);

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        return view('pages.reservasi.ruangan.edit', compact('reservasi'));
    }

    public function show($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        return view('pages.reservasi.ruangan.detail', compact('reservasi'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'unit' => 'required|string|max:50',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tanggal' => 'required|date',
            'ruang' => 'required|string|max:50',
            'approval' => 'nullable|in:Pending,Approved,Rejected',
        ], [
            'jam_selesai.after' => 'Jam Selesai harus lebih besar dari Jam Mulai.',
        ]);

        $isOverlap = ReservasiRuangan::where('id', '<>', $id)
            ->where('ruang', $validated['ruang'])
            ->where('tanggal', $validated['tanggal'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                            ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })->exists();

        if ($isOverlap) {
            return back()->withErrors(['Maaf, waktu yang anda inputkan sudah ada yang mendaftar.'])->withInput();
        }

        $reservasi = ReservasiRuangan::findOrFail($id);
        $reservasi->update($validated);

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservasi = ReservasiRuangan::findOrFail($id);
        $reservasi->delete();

        return redirect()->route('reservasi.ruangan.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
