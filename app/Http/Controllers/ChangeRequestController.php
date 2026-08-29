<?php

namespace App\Http\Controllers;

use App\Models\ApprovalMapping;
use App\Models\ChangeRequest;
use App\Models\User;
use App\Models\Setting;
use App\Helpers\PermissionHelper;
use App\Notifications\ChangeRequestApprovalNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ChangeRequestController extends Controller
{
    public function __construct()
    {
        // Akses baca/buat mengikuti aturan jabatan (IT, peminta, approver) via PermissionHelper::canManageChangeRequest.
        // Edit/hapus diverifikasi per-record di method (pemilik atau IT).
    }

    private function isIT()
    {
        $user = Auth::user();
        return $user && strtolower(trim($user->unit ?? '')) == 'teknologi dan informasi';
    }

    /* Manajer (jabatan mengandung kata "manajer") diberi akses penuh CRUD, termasuk Manajer Umum. */
    private function isManager($user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user) return false;
        return str_contains(strtolower(trim($user->jabatan ?? '')), 'manajer');
    }

    /* Level approval yang bisa dilakukan user login terhadap CR (0 = tidak berhak) */
    private function approvableLevel(ChangeRequest $cr)
    {
        $user = Auth::user();
        if (!$user) return 0;

        $mapping = ApprovalMapping::findForRequester($cr->user_id, $cr->jabatan_id, $cr->jabatan ?? '');

        if ($cr->approval_1_status === 'Menunggu' && $mapping) {
            if (ApprovalMapping::matchesApprover($mapping, $user->id, $user->jabatan_id, $user->jabatan ?? '')) {
                return 1;
            }
        }

        if ($cr->approval_1_status === 'Disetujui' && $cr->approval_2_status === 'Menunggu') {
            if ($this->isStage2User($user)) {
                return 2;
            }
        }

        return 0;
    }

    private function isStage2User($user)
    {
        // User khusus terpilih dari panel (settings)
        $stage2UserId = Setting::get('stage2_user_id');
        if ($stage2UserId && $user->id == $stage2UserId) return true;

        // Approver 2 yang dipilih langsung pada approval mapping.
        if (ApprovalMapping::where('approver_user_id', $user->id)
            ->whereRaw('LOWER(TRIM(approver_jabatan)) = ?', [strtolower(trim(config('approvals.stage2_jabatan')))])
            ->exists()) {
            return true;
        }

        $stage2Id = config('approvals.stage2_jabatan_id');
        if ($stage2Id && $user->jabatan_id == $stage2Id) return true;

        return strtolower(trim($user->jabatan ?? '')) === strtolower(trim(config('approvals.stage2_jabatan')));
    }

    private function isApproverStage2($mapping)
    {
        if (!$mapping) return false;

        // User approver = user tahap 2 terpilih
        $stage2UserId = Setting::get('stage2_user_id');
        if ($stage2UserId && $mapping->approver_user_id == $stage2UserId) return true;

        $stage2Id = config('approvals.stage2_jabatan_id');
        if ($stage2Id && $mapping->approver_jabatan_id == $stage2Id) return true;

        return strtolower(trim($mapping->approver_jabatan ?? '')) === strtolower(trim(config('approvals.stage2_jabatan')));
    }

    private function notifyUsersByJabatanId($jabatanId, $message, $cr)
    {
        User::where('jabatan_id', $jabatanId)
            ->get()
            ->each->notify(new ChangeRequestApprovalNotification($cr, $message));
    }

    private function notifyStage2($message, $cr)
    {
        // User khusus terpilih dari panel
        $stage2UserId = Setting::get('stage2_user_id');

        if ($stage2UserId) {
            $user = User::find($stage2UserId);
            if ($user) $user->notify(new ChangeRequestApprovalNotification($cr, $message));
        } elseif (config('approvals.stage2_jabatan_id')) {
            $this->notifyUsersByJabatanId(config('approvals.stage2_jabatan_id'), $message, $cr);
        } else {
            $this->notifyUsersByJabatan(config('approvals.stage2_jabatan'), $message, $cr);
        }
    }

    private function notifyApprover1($mapping, $message, $cr)
    {
        if ($mapping->approver_user_id) {
            $user = User::find($mapping->approver_user_id);
            if ($user) $user->notify(new ChangeRequestApprovalNotification($cr, $message));
        } elseif ($mapping->approver_jabatan_id) {
            $this->notifyUsersByJabatanId($mapping->approver_jabatan_id, $message, $cr);
        } else {
            $this->notifyUsersByJabatan($mapping->approver_jabatan, $message, $cr);
        }
    }

    private function notifyUsersByJabatan($jabatan, $message, $cr)
    {
        User::whereRaw('LOWER(jabatan) = ?', [strtolower(trim($jabatan))])
            ->get()
            ->each->notify(new ChangeRequestApprovalNotification($cr, $message));
    }

    private function notifyRequester(ChangeRequest $cr, $decision, $level)
    {
        if ($cr->user) {
            $cr->user->notify(new ChangeRequestApprovalNotification(
                $cr,
                'Change Request #' . $cr->id . ' Anda ' . $decision . ' pada Tahap ' . $level . '.',
            ));
        }
    }

    public function index(Request $request)
    {
        if (!PermissionHelper::canManageChangeRequest()) {
            abort(403);
        }

        if ($request->ajax()) {
            $columns = ['id', 'no_tiket', 'created_at', 'permintaan_fitur', 'status_pengerjaan'];

            $user = Auth::user();
            $isIT = $this->isIT();
            $myJabatan = strtolower(trim($user->jabatan ?? ''));

            $query = ChangeRequest::query();

            if (!$isIT) {
                $query->where(function ($q) use ($user, $myJabatan) {
                    // Request milik sendiri
                    $q->where('user_id', $user->id);

                    // User adalah approver (atasan langsung) untuk request ini
                    // tanpa syarat status -> request yang sudah disetujui tetap tampil sebagai log/riwayat
                    $q->orWhere(function ($q2) use ($user, $myJabatan) {
                        // cocok lewat user approver (mapping per-akun)
                        $requesterUserIds = ApprovalMapping::where('approver_user_id', $user->id)
                            ->pluck('requester_user_id');
                        if ($requesterUserIds->count()) {
                            $q2->whereIn('user_id', $requesterUserIds);
                        }

                        // cocok lewat jabatan_id (dari HRIS)
                        $requesterIds = ApprovalMapping::where('approver_jabatan_id', $user->jabatan_id)
                            ->pluck('requester_jabatan_id');
                        $q2->orWhereIn('jabatan_id', $requesterIds);

                        // fallback teks untuk CR lama yang belum punya jabatan_id
                        $requesterTexts = ApprovalMapping::whereRaw('LOWER(approver_jabatan) = ?', [$myJabatan])
                            ->pluck('requester_jabatan');
                        if ($requesterTexts->count()) {
                            $q2->orWhere(function ($q4) use ($requesterTexts) {
                                $q4->whereNull('jabatan_id')->whereIn('jabatan', $requesterTexts);
                            });
                        }
                    });

                    // Approver 1/2: tampilkan juga CR yang sudah di-approve oleh user ini (riwayat)
                    $q->orWhere('approval_1_by', $user->name);
                    $q->orWhere('approval_2_by', $user->name);

                    // User adalah Manajer Umum (tahap 2) -> lihat CR menunggu tahap 2 + CR yang sudah di-approve oleh dirinya
                    if ($this->isStage2User($user)) {
                        $q->orWhere(function ($q3) use ($user) {
                            $q3->where('approval_1_status', 'Disetujui')
                                ->where('approval_2_status', 'Menunggu');
                        });
                        $q->orWhere('approval_2_by', $user->name);
                    }
                });
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
                        ->orWhere('no_tiket', 'like', "%{$search}%");
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
            $isManager = $this->isManager();
            $isStage2 = $this->isStage2User($user);
            foreach ($records as $item) {
                $isOwner = $item->user_id === $user->id;
                $wasApprover = ($item->approval_1_by ?? '') === $user->name || ($item->approval_2_by ?? '') === $user->name;
                $data[] = [
                    'id'                    => $item->id,
                    'no_cr'                 => $item->id,
                    'jabatan'               => $item->user->jabatan ?? $item->jabatan ?? '-',
                    'permintaan_fitur'      => $item->permintaan_fitur ?? '-',
                    'status_pengerjaan'     => $item->status_pengerjaan ?? 'Open',
                    'no_tiket'              => $item->no_tiket ?? 'No Tiket',
                    'approval_1_status'     => $item->approval_1_status ?? 'Menunggu',
                    'approval_1_by'         => $item->approval_1_by ?? null,
                    'approval_2_status'     => $item->approval_2_status ?? 'Menunggu',
                    'approvable_level'      => $this->approvableLevel($item),
                    'created_at_timestamp'  => Carbon::parse($item->created_at)->timestamp,
                    'tanggal_formatted'     => '
                    <div class="flex flex-col">
                        <span>' . Carbon::parse($item->created_at)->translatedFormat('d F Y') . '</span>
                    </div>
                ',
                    'can_update'            => $isIT || ($isOwner && !$wasApprover && $item->approval_1_status !== 'Disetujui'),
                    'can_read'              => true,
                    'can_delete'            => $isIT || ($isOwner && !$wasApprover && $item->approval_1_status !== 'Disetujui'),
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
        if (!PermissionHelper::canManageChangeRequest()) {
            abort(403);
        }

        $user = Auth::user();
        $mapping = ApprovalMapping::findForRequester($user->id, $user->jabatan_id, $user->jabatan ?? '');
        $approverName = $mapping
            ? ($mapping->approverUser->name ?? $mapping->approver_jabatan)
            : null;

        return view('pages.change-request.create', [
            'canRequest'   => (bool) $mapping,
            'approverName' => $approverName,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'permintaan_fitur' => 'required|in:Sigercep,HRIS,SIMRS,Website',
            'deskripsi'      => 'required|string',
            'file_pendukung' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        $user = Auth::user();

        // Hanya user / jabatan yang terdaftar di mapping yang boleh mengajukan
        $mapping = ApprovalMapping::findForRequester($user->id, $user->jabatan_id, $user->jabatan ?? '');
        if (!$mapping) {
            return redirect()->route('change-request.index')
                ->with('error', 'Hanya user / jabatan yang terdaftar yang dapat mengajukan Change Request.');
        }

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

        $cr = ChangeRequest::create([
            'user_id'           => $user->id,
            'nama'              => ucfirst($user->name),
            'jabatan'           => $user->jabatan ?? '-',
            'jabatan_id'        => $user->jabatan_id,
            'permintaan_fitur'  => $request->permintaan_fitur,
            'deskripsi'         => $request->deskripsi,
            'file_pendukung'    => $filePendukung,
            'file_path'         => $filePath,
            'status_pengerjaan' => 'Open',
            'approval_1_status' => 'Menunggu',
            'approval_2_status' => 'Menunggu',
        ]);

        // Auto-generate nomor tiket kecuali SIMRS
        if ($request->permintaan_fitur !== 'SIMRS') {
            $cr->no_tiket = 'CR-' . str_pad($cr->id, 4, '0', STR_PAD_LEFT);
            $cr->save();
        }

        $this->notifyApprover1(
            $mapping,
            'Change Request #' . $cr->id . ' dari ' . ucwords(str_replace('.', ' ', $cr->nama)) . ' menunggu persetujuan Anda.',
            $cr,
        );

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil dikirim dan menunggu approval.');
    }

    public function show(string $id)
    {
        $user = Auth::user();
        $changeRequest = ChangeRequest::findOrFail($id);
        $approvableLevel = $this->approvableLevel($changeRequest);

        $isIT = $this->isIT();
        $isManager = $this->isManager();
        $isOwner = $changeRequest->user_id === $user->id;
        $isStage2 = $this->isStage2User($user);
        // Akses: IT, Manager, Owner, Stage 2, atau Approver yang bereliasi
        if (!$isIT && !$isManager && !$isOwner && !$isStage2) {
            // Cek apakah user adalah approver yang sudah melakukan aksi (riwayat)
            $wasApprover = ($changeRequest->approval_1_by ?? '') === $user->name
                || ($changeRequest->approval_2_by ?? '') === $user->name;

            if (!$wasApprover) {
                // Cek semua mapping di mana user ini adalah approver
                $mappings = ApprovalMapping::where('approver_user_id', $user->id)
                    ->orWhere('approver_jabatan_id', $user->jabatan_id)
                    ->orWhereRaw('LOWER(approver_jabatan) = ?', [strtolower(trim($user->jabatan ?? ''))])
                    ->get();

                $isApprover = false;
                foreach ($mappings as $mapping) {
                    if (!ApprovalMapping::matchesApprover($mapping, $user->id, $user->jabatan_id, $user->jabatan ?? '')) {
                        continue;
                    }
                    // Pastikan mapping ini relevan dengan CR creator
                    if ($mapping->requester_user_id && $mapping->requester_user_id == $changeRequest->user_id) {
                        $isApprover = true;
                        break;
                    }
                    if ($mapping->requester_jabatan_id && $mapping->requester_jabatan_id == $changeRequest->jabatan_id) {
                        $isApprover = true;
                        break;
                    }
                    if ($mapping->requester_jabatan && strtolower(trim($mapping->requester_jabatan)) === strtolower(trim($changeRequest->jabatan ?? ''))) {
                        $isApprover = true;
                        break;
                    }
                }

                if (!$isApprover) {
                    abort(403, 'Akses ditolak.');
                }
            }
        }

        return view('pages.change-request.detail', compact('changeRequest', 'approvableLevel'));
    }

    public function showFile($id)
    {
        $user = Auth::user();
        $changeRequest = ChangeRequest::findOrFail($id);

        $wasApprover = ($changeRequest->approval_1_by ?? '') === $user->name
            || ($changeRequest->approval_2_by ?? '') === $user->name;

        $isApproverMapped = false;
        if (!$wasApprover) {
            $mappings = ApprovalMapping::where('approver_user_id', $user->id)
                ->orWhere('approver_jabatan_id', $user->jabatan_id)
                ->orWhereRaw('LOWER(approver_jabatan) = ?', [strtolower(trim($user->jabatan ?? ''))])
                ->get();

            foreach ($mappings as $mapping) {
                if (!ApprovalMapping::matchesApprover($mapping, $user->id, $user->jabatan_id, $user->jabatan ?? '')) {
                    continue;
                }
                if ($mapping->requester_user_id && $mapping->requester_user_id == $changeRequest->user_id) {
                    $isApproverMapped = true;
                    break;
                }
                if ($mapping->requester_jabatan_id && $mapping->requester_jabatan_id == $changeRequest->jabatan_id) {
                    $isApproverMapped = true;
                    break;
                }
                if ($mapping->requester_jabatan && strtolower(trim($mapping->requester_jabatan)) === strtolower(trim($changeRequest->jabatan ?? ''))) {
                    $isApproverMapped = true;
                    break;
                }
            }
        }

        if (!$this->isIT() && $changeRequest->user_id !== $user->id && !$wasApprover && !$isApproverMapped && !$this->isStage2User($user)) {
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

    public function approve(Request $request, string $id)
    {
        $cr = ChangeRequest::findOrFail($id);
        $level = $this->approvableLevel($cr);
        if ($level === 0) {
            abort(403, 'Anda tidak berhak menyetujui request ini.');
        }

        $request->validate([
            'decision' => 'required|in:Disetujui,Ditolak',
            'tanda_tangan' => 'required_if:decision,Disetujui|nullable|string',
        ]);

        $user = Auth::user();
        $isRejected = $request->decision === 'Ditolak';
        $field = 'approval_' . $level;

        $cr->{$field . '_status'} = $request->decision;
        $cr->{$field . '_by'} = $user->name;
        $cr->{$field . '_at'} = now();
        $cr->{$field . '_ttd'} = $request->tanda_tangan;

        if ($level === 1) {
            if ($isRejected) {
                $cr->approval_2_status = 'Ditolak';
                $cr->save();
                $this->notifyRequester($cr, 'Ditolak', 1);
            } else {
                $mapping = ApprovalMapping::findForRequester($cr->user_id, $cr->jabatan_id, $cr->jabatan ?? '');

                if ($this->isApproverStage2($mapping)) {
                    // Atasan langsung = Manajer Umum, tahap 2 otomatis disetujui
                    $cr->approval_2_status = 'Disetujui';
                    $cr->approval_2_by = $user->name;
                    $cr->approval_2_at = now();
                    $cr->approval_2_ttd = $request->tanda_tangan;
                    $cr->save();
                    $this->notifyRequester($cr, 'Disetujui', 2);
                } else {
                    $cr->approval_2_status = 'Menunggu';
                    $cr->save();
                    $this->notifyRequester($cr, 'Disetujui', 1);
                    $this->notifyStage2(
                        'Change Request #' . $cr->id . ' dari ' . ucwords(str_replace('.', ' ', $cr->nama)) . ' menunggu persetujuan Anda.',
                        $cr,
                    );
                }
            }
        } else {
            // Level 2
            $cr->save();
            $this->notifyRequester($cr, $isRejected ? 'Ditolak' : 'Disetujui', 2);
        }

        return redirect()->back()->with('success', 'Approval Tahap ' . $level . ' berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        $user = Auth::user();
        $isIT = $this->isIT();
        $isManager = $this->isManager();
        $isOwner = $changeRequest->user_id === $user->id && PermissionHelper::canManageChangeRequest() && $changeRequest->approval_1_status !== 'Disetujui';

        if (!$isIT && !$isOwner) {
            abort(403, 'Anda hanya dapat mengedit Change Request milik sendiri.');
        }

        return view('pages.change-request.edit', compact('changeRequest', 'isIT'));
    }

    public function update(Request $request, string $id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        $user = Auth::user();
        $isIT = $this->isIT();
        $isManager = $this->isManager();
        $isOwner = $changeRequest->user_id === $user->id && PermissionHelper::canManageChangeRequest() && $changeRequest->approval_1_status !== 'Disetujui';

        if (!$isIT && !$isOwner) {
            abort(403, 'Anda hanya dapat mengedit Change Request milik sendiri.');
        }

        if (!$isIT) {
            $request->validate([
                'permintaan_fitur'  => 'required|in:Sigercep,HRIS,SIMRS,Website',
                'deskripsi'         => 'required|string',
                'file_pendukung'    => 'nullable|file|mimes:pdf|max:20480',
            ]);
        }

        $filePendukung = $changeRequest->file_pendukung;
        $filePath      = $changeRequest->file_path;

        if (!$isIT && $request->hasFile('file_pendukung')) {
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

        if ($isIT) {
            $request->validate([
                'status_pengerjaan' => 'required|in:Open,In Progress,Pending,QC,Done,Closed',
                'no_tiket'          => 'nullable|string|max:100',
                'created_at'        => 'nullable|date',
            ]);

            $updateData = [
                'status_pengerjaan' => $request->status_pengerjaan,
                'no_tiket'          => $request->no_tiket,
            ];

            if ($request->filled('created_at')) {
                try {
                    $updateData['created_at'] = Carbon::createFromFormat('d-m-Y', $request->created_at)->startOfDay();
                } catch (\Exception $e) {
                    $updateData['created_at'] = Carbon::parse($request->created_at)->startOfDay();
                }
            }
        } else {
            $updateData = [
                'permintaan_fitur'  => $request->permintaan_fitur,
                'deskripsi'         => $request->deskripsi,
                'file_pendukung'    => $filePendukung,
                'file_path'         => $filePath,
            ];
        }

        $changeRequest->update($updateData);

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $changeRequest = ChangeRequest::findOrFail($id);
        $user = Auth::user();
        $isIT = $this->isIT();
        $isManager = $this->isManager();
        $isOwner = $changeRequest->user_id === $user->id && PermissionHelper::canManageChangeRequest() && $changeRequest->approval_1_status !== 'Disetujui';

        if (!$isIT && !$isOwner) {
            abort(403, 'Anda hanya dapat menghapus Change Request milik sendiri.');
        }

        if ($changeRequest->file_path && Storage::disk('public')->exists($changeRequest->file_path)) {
            Storage::disk('public')->delete($changeRequest->file_path);
        }

        $changeRequest->delete();

        return redirect()->route('change-request.index')->with('success', 'Change Request berhasil dihapus.');
    }
}
