<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanDokumen;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PermissionHelper;

class PengajuanDokumenController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pengajuan_dokumen,read')->only(['index', 'show']);
        $this->middleware('permission:pengajuan_dokumen,create')->only(['create', 'store']);
        $this->middleware('permission:pengajuan_dokumen,update')->only(['edit', 'update']);
        $this->middleware('permission:pengajuan_dokumen,delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = [
                'judul_dokumen',
                'jenis_dokumen',
                'nomor_dokumen',
                'kategori_pengajuan',
                'tanggal_pengajuan',
                'diajukan_oleh',
            ];

            $query = PengajuanDokumen::query();

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->whereDate('tanggal_pengajuan', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->whereDate('tanggal_pengajuan', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('judul_dokumen', 'like', "%{$search}%")
                        ->orWhere('jenis_dokumen', 'like', "%{$search}%")
                        ->orWhere('nomor_dokumen', 'like', "%{$search}%")
                        ->orWhere('kategori_pengajuan', 'like', "%{$search}%")
                        ->orWhere('diajukan_oleh', 'like', "%{$search}%");
                });
            }

            $recordsTotal = PengajuanDokumen::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'tanggal_pengajuan';
                $orderDir = $request->order[0]['dir'] ?? 'desc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('tanggal_pengajuan', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('pengajuan_dokumen', 'update');
            $canRead = PermissionHelper::canAccess('pengajuan_dokumen', 'read');
            $canDelete = PermissionHelper::canAccess('pengajuan_dokumen', 'delete');

            $data = [];

            foreach ($records as $item) {
                $data[] = [
                    'id' => $item->id,
                    'judul_dokumen' => Str::limit($item->judul_dokumen, 50),
                    'jenis_dokumen' => $item->jenis_dokumen,
                    'nomor_dokumen' => $item->nomor_dokumen,
                    'kategori_pengajuan' => $item->kategori_pengajuan,
                    'tanggal_pengajuan_timestamp' => Carbon::parse($item->tanggal_pengajuan)->timestamp,
                    'tanggal_pengajuan_formatted' => Carbon::parse($item->tanggal_pengajuan)->translatedFormat('d F Y'),
                    'diajukan_oleh' => $item->diajukan_oleh,
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

        return view('pages.komite-mutu.pengajuan-dokumen.index');
    }

    public function create()
    {
        return view('pages.komite-mutu.pengajuan-dokumen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:255',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:255',
            'alasan_pengajuan' => 'required|string|max:255',
            'bagian_yang_direvisi' => 'nullable|string|max:255',
            'sebelum_revisi' => 'nullable|string|max:255',
            'usulan_revisi' => 'nullable|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_spo' => 'required|file|max:20480',
        ]);

        $ext = strtolower($request->file('file_spo')->getClientOriginalExtension());

        if (!in_array($ext, ['doc', 'docx'])) {
            return back()->withErrors([
                'file_spo' => 'File harus berformat Word (.doc atau .docx)'
            ]);
        }

        $file = $request->file('file_spo');
        $originalName = $file->getClientOriginalName();

        $validated['file_spo'] = $originalName;
        $validated['file_path'] = 'pengajuan-dokumen/' . $originalName;

        if (Storage::disk('public')->exists($validated['file_path'])) {
            return back()->withErrors([
                'file_spo' => 'File dengan nama ini sudah ada.'
            ]);
        }

        Storage::disk('public')->putFileAs(
            'pengajuan-dokumen',
            $file,
            $originalName
        );

        PengajuanDokumen::create($validated);

        return redirect()
            ->route('komite-mutu.pengajuan-dokumen.index')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function show(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.detail', compact('pengajuanDokumen'));
    }

    public function showFile($id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        $filePath = storage_path("app/public/{$pengajuanDokumen->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        $filename = trim(
            ($pengajuanDokumen->unit ? $pengajuanDokumen->unit . ' - ' : '') .
                $pengajuanDokumen->file_spo
        );

        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function edit(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);
        return view('pages.komite-mutu.pengajuan-dokumen.edit', compact('pengajuanDokumen'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'jenis_dokumen' => 'required|string|max:255',
            'permintaan_pengajuan' => 'required|string|max:255',
            'kategori_pengajuan' => 'required|string|max:255',
            'nomor_dokumen' => 'required|string|max:100',
            'judul_dokumen' => 'required|string|max:255',
            'nomor_revisi' => 'required|string|max:50',
            'alasan_pengajuan' => 'required|string|max:500',
            'bagian_yang_direvisi' => 'required|string|max:500',
            'sebelum_revisi' => 'required|string|max:1000',
            'usulan_revisi' => 'required|string|max:1000',
            'tanggal_pengajuan' => 'required|date',
            'diajukan_oleh' => 'required|string|max:255',
            'diperiksa_oleh' => 'required|string|max:255',
            'disetujui_oleh' => 'required|string|max:255',
            'file_spo' => 'nullable|file|max:20480',
        ]);

        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        if ($request->hasFile('file_spo')) {
            $ext = strtolower($request->file('file_spo')->getClientOriginalExtension());

            if (!in_array($ext, ['doc', 'docx'])) {
                return back()->withErrors([
                    'file_spo' => 'File harus berformat Word (.doc atau .docx)'
                ]);
            }

            if (
                $pengajuanDokumen->file_path &&
                Storage::disk('public')->exists($pengajuanDokumen->file_path)
            ) {
                Storage::disk('public')->delete($pengajuanDokumen->file_path);
            }

            $file = $request->file('file_spo');
            $originalName = $file->getClientOriginalName();
            $validated['file_spo'] = $originalName;
            $validated['file_path'] = 'pengajuan-dokumen/' . $originalName;

            Storage::disk('public')->putFileAs(
                'pengajuan-dokumen',
                $file,
                $originalName
            );
        }

        $pengajuanDokumen->update($validated);

        return redirect()
            ->route('komite-mutu.pengajuan-dokumen.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $pengajuanDokumen = PengajuanDokumen::findOrFail($id);

        if (Storage::disk('public')->exists($pengajuanDokumen->file_path)) {
            Storage::disk('public')->delete($pengajuanDokumen->file_path);
        }

        $pengajuanDokumen->delete();

        return redirect(route('komite-mutu.pengajuan-dokumen.index') . '?deleted=1')->with('success', 'Data berhasil dihapus.');
    }
}