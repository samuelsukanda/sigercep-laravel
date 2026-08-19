<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class VisitasiController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:visitasi,read')->only(['index', 'show']);
        $this->middleware('permission:visitasi,create')->only(['create', 'store']);
        $this->middleware('permission:visitasi,update')->only(['edit', 'update']);
        $this->middleware('permission:visitasi,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['nama', 'tim', 'tanggal', 'kendala'];

            $query = Visitasi::query();

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
                        ->orWhere('tim', 'like', "%{$search}%")
                        ->orWhere('kendala', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Visitasi::count();
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

            $canUpdate = PermissionHelper::canAccess('visitasi', 'update');
            $canRead = PermissionHelper::canAccess('visitasi', 'read');
            $canDelete = PermissionHelper::canAccess('visitasi', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'nama' => ucwords(strtolower($item->nama)),
                    'tim' => $item->tim,
                    'tanggal_timestamp' => Carbon::parse($item->tanggal)->timestamp,
                    'tanggal_formatted' => Carbon::parse($item->tanggal)->translatedFormat('d F Y'),
                    'kendala' => Str::limit(strtolower($item->kendala ?? ''), 50),
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

        return view('pages.visitasi.index');
    }

    public function create()
    {
        return view('pages.visitasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        Visitasi::create($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.detail', compact('visitasi'));
    }

    public function edit(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);
        return view('pages.visitasi.edit', compact('visitasi'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
            'tim' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'kendala' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $visitasi = Visitasi::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
                Storage::disk('public')->delete($visitasi->foto);
            }

            $file = $request->file('foto');
            $namaFile = 'visitasi-' . Carbon::now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images/visitasi', $namaFile, 'public');
            $validated['foto'] = $path;
        }

        $visitasi->update($validated);

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $visitasi = Visitasi::findOrFail($id);

        if ($visitasi->foto && Storage::disk('public')->exists($visitasi->foto)) {
            Storage::disk('public')->delete($visitasi->foto);
        }

        $visitasi->delete();

        return redirect()->route('visitasi.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
