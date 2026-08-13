<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use App\Helpers\PermissionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ChangeRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:change_request,read')->only(['index', 'show']);
        $this->middleware('permission:change_request,create')->only(['create', 'store']);
        $this->middleware('permission:change_request,update')->only(['edit', 'update']);
        $this->middleware('permission:change_request,delete')->only(['destroy']);
    }

    private function isIT()
    {
        $user = Auth::user();
        return $user && strtolower(trim($user->unit ?? '')) == 'teknologi dan informasi';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['id', 'tanggal_formatted', 'nama', 'jabatan', 'permintaan_fitur', 'deskripsi', 'status_dokumen', 'status_pengerjaan', 'pic_request', 'no_tiket'];

            $user = Auth::user();
            $isIT = $this->isIT();

            $query = ChangeRequest::query();

            // User biasa hanya melihat CR milik sendiri
            if (!$isIT) {
                $query->where('user_id', $user->id);
            }

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('status_dokumen')) {
                $query->where('status_dokumen', $request->status_dokumen);
            }

            if ($request->filled('status_pengerjaan')) {
                $query->where('status_pengerjaan', $request->status_pengerjaan);
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhere('permintaan_fitur', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%")
                        ->orWhere('no_tiket', 'like', "%{$search}%")
                        ->orWhere('pic_request', 'like', "%{$search}%");
                });
            }

            $totalQuery = ChangeRequest::query();
            if (!$isIT) {
                $totalQuery->where('user_id', $user->id);
            }
            $recordsTotal = $totalQuery->count();
            $recordsFiltered = $query->count();

            $query->orderBy('created_at', 'desc');

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $records = $query->skip($start)->take($length)->get();

            $canUpdate = PermissionHelper::canAccess('change_request', 'update');
            $canRead   = PermissionHelper::canAccess('change_request', 'read');
            $canDelete = PermissionHelper::canAccess('change_request', 'delete');

            $data = [];
            foreach ($records as $item) {
                $data[] = [
                    'id'                    => $item->id,
                    'no_cr'                 => $item->id,
                    'nama'                  => ucfirst($item->nama),
                    'jabatan'               => $item->user->jabatan ?? $item->jabatan ?? '-',
                    'permintaan_fitur'      => $item->permintaan_fitur ?? '-',
                    'deskripsi'             => $item->deskripsi,
                    'status_dokumen'        => $item->status_dokumen ?? 'Dalam Proses',
                    'status_pengerjaan'     => $item->status_pengerjaan ?? 'Open',
                    'pic_request'           => $item->pic_request ?? '-',
                    'no_tiket'              => $item->no_tiket ?? 'No Tiket',
                    'created_at_timestamp'  => Carbon::parse($item->created_at)->timestamp,
                    'tanggal_formatted'     => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->created_at)->translatedFormat('d F Y') . '</span>
                    </div>
                ',
                    'can_update'            => $canUpdate,
                    'can_read'              => $canRead,
                    'can_delete'            => $canDelete,
                ];
            }

            return response()->json([
                'draw'            => intval($request->draw),
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $data,
            ]);
        }

        $isFiltered = $request->hasAny(['periode_dari', 'periode_sampai', 'status']);

        return view('pages.change-request.index', compact('isFiltered'));
    }

    public function create()
    {
        return view('pages.change-request.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_fitur' => 'required|in:Sigercep,HRIS,SIMRS,Website',
            'deskripsi'      => 'required|string',
            'file_pendukung' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $user = Auth::user();

        $filePendukung = null;
        $filePath      = null;

        if ($request->hasFile('file_pendukung')) {
            $file         = $request->file('file_pendukung');
            $originalName = $file->getClientOriginalName();
            $folderPath   = 'change-request';
            $targetPath   = "$folderPath/$originalName";

            if (Storage::disk('public')->exists($targetPath)) {
                // Tambahkan timestamp untuk menghindari duplikasi
                $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.pdf';
                $targetPath   = "$folderPath/$originalName";
            }

            Storage::disk('public')->putFileAs($folderPath, $file, $originalName);
            $filePendukung = $originalName;
            $filePath      = $targetPath;
        }

        ChangeRequest::create([
            'user_id'           => $user->id,
            'nama'              => ucfirst($user->name),
            'jabatan'           => $user->jabatan ?? '-',
            'permintaan_fitur'  => $request->permintaan_fitur,
            'deskripsi'         => $request->deskripsi,
            'file_pendukung'    => $filePendukung,
            'file_path'         => $filePath,
            'status_dokumen'    => 'Dalam Proses',
            'status_pengerjaan' => 'Open',
        ]);

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil dikirim.');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $changeRequest = ChangeRequest::findOrFail($id);

        // User biasa hanya bisa melihat milik sendiri
        if (!$this->isIT() && $changeRequest->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('pages.change-request.detail', compact('changeRequest'));
    }

    public function showFile($id)
    {
        $user = Auth::user();
        $changeRequest = ChangeRequest::findOrFail($id);

        if (!$this->isIT() && $changeRequest->user_id !== $user->id) {
            abort(403, 'Akses ditolak.');
        }

        if (!$changeRequest->file_path) {
            abort(404, 'File tidak tersedia.');
        }

        $filePath = storage_path("app/public/{$changeRequest->file_path}");

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->file($filePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $changeRequest->file_pendukung . '"',
        ]);
    }

    public function edit(string $id)
    {
        // Hanya user IT yang boleh edit
        if (!$this->isIT()) {
            abort(403, 'Hanya user IT yang dapat mengedit Change Request.');
        }

        $changeRequest = ChangeRequest::findOrFail($id);
        return view('pages.change-request.edit', compact('changeRequest'));
    }

    public function update(Request $request, string $id)
    {
        if (!$this->isIT()) {
            abort(403, 'Hanya user IT yang dapat mengedit Change Request.');
        }

        $changeRequest = ChangeRequest::findOrFail($id);

        $request->validate([
            'status_dokumen'    => 'required|in:Terpenuhi,Dalam Proses,Tidak Ada',
            'status_pengerjaan' => 'required|in:Open,In Progress,Pending,QC,Done,Closed',
            'permintaan_fitur'  => 'required|in:Sigercep,HRIS,SIMRS,Website',
            'no_tiket'          => 'nullable|string|max:100',
            'pic_request'       => 'nullable|string|max:100',
            'deskripsi'         => 'required|string',
            'created_at'        => 'nullable|date',
            'file_pendukung'    => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $filePendukung = $changeRequest->file_pendukung;
        $filePath      = $changeRequest->file_path;

        if ($request->hasFile('file_pendukung')) {
            // Hapus file lama
            if ($changeRequest->file_path && Storage::disk('public')->exists($changeRequest->file_path)) {
                Storage::disk('public')->delete($changeRequest->file_path);
            }

            $file         = $request->file('file_pendukung');
            $originalName = $file->getClientOriginalName();
            $folderPath   = 'change-request';
            $targetPath   = "$folderPath/$originalName";

            if (Storage::disk('public')->exists($targetPath)) {
                $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '_' . time() . '.pdf';
                $targetPath   = "$folderPath/$originalName";
            }

            Storage::disk('public')->putFileAs($folderPath, $file, $originalName);
            $filePendukung = $originalName;
            $filePath      = $targetPath;
        }

        $updateData = [
            'deskripsi'         => $request->deskripsi,
            'permintaan_fitur'  => $request->permintaan_fitur,
            'status_dokumen'    => $request->status_dokumen,
            'status_pengerjaan' => $request->status_pengerjaan,
            'no_tiket'          => $request->no_tiket,
            'pic_request'       => $request->pic_request,
            'file_pendukung'    => $filePendukung,
            'file_path'         => $filePath,
        ];

        if ($request->filled('created_at')) {
            try {
                $updateData['created_at'] = Carbon::createFromFormat('d-m-Y', $request->created_at)->startOfDay();
            } catch (\Exception $e) {
                $updateData['created_at'] = Carbon::parse($request->created_at)->startOfDay();
            }
        }

        $changeRequest->update($updateData);

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);

        if ($changeRequest->file_path && Storage::disk('public')->exists($changeRequest->file_path)) {
            Storage::disk('public')->delete($changeRequest->file_path);
        }

        $changeRequest->delete();

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil dihapus.');
    }
}
