<?php

namespace App\Http\Controllers;

use App\Models\ApprovalMapping;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class ApprovalMappingController extends Controller
{
    private function ensureIT()
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Hanya user IT yang dapat mengakses panel ini.');
        }

        $name = strtolower(trim($user->name ?? ''));
        $unit = strtolower(trim($user->unit ?? ''));
        $jabatan = strtolower(trim($user->jabatan ?? ''));

        $isSammuel = ($name === 'sammuel' && $unit === 'teknologi dan informasi' && $jabatan === 'operasional it technical support');
        $isDeden = ($name === 'deden eka nugraha' && $unit === 'teknologi dan informasi' && $jabatan === 'spv it');

        if (!$isSammuel && !$isDeden) {
            abort(403, 'Hanya user IT yang berwenang yang dapat mengakses panel ini.');
        }
    }

    public function index()
    {
        $this->ensureIT();
        $mappings = ApprovalMapping::with(['requesterUser', 'approverUser'])
            ->orderBy('requester_jabatan')->get();
        $stage2 = config('approvals.stage2_jabatan');
        $stage2UserId = Setting::get('stage2_user_id');
        $users = User::orderBy('name')->get(['id', 'name', 'username', 'unit', 'jabatan']);
        $jabatanList = collect(User::whereNotNull('jabatan')->pluck('jabatan'))
            ->concat($mappings->pluck('requester_jabatan'))
            ->concat($mappings->pluck('approver_jabatan'))
            ->filter()
            ->unique()
            ->values();
        return view('pages.approval-mapping.index', compact('mappings', 'stage2', 'stage2UserId', 'users', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $this->ensureIT();
        $data = $request->validate([
            'requester_jabatan' => 'required|string|max:100|unique:approval_mappings,requester_jabatan',
            'approver_jabatan'  => 'required|string|max:100',
            'requester_user_id' => 'nullable|exists:users,id',
            'approver_user_id'  => 'nullable|exists:users,id',
        ]);
        $data['requester_jabatan_id'] = $this->resolveJabatanId($data['requester_jabatan']);
        $data['approver_jabatan_id'] = $this->resolveJabatanId($data['approver_jabatan']);
        ApprovalMapping::create($data);
        return redirect(route('approval-mapping.index') . '?saved=1')->with('success', 'Mapping approver 1 berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->ensureIT();
        $mapping = ApprovalMapping::findOrFail($id);
        $data = $request->validate([
            'requester_jabatan' => 'required|string|max:100|unique:approval_mappings,requester_jabatan,' . $mapping->id,
            'approver_jabatan'  => 'required|string|max:100',
            'requester_user_id' => 'nullable|exists:users,id',
            'approver_user_id'  => 'nullable|exists:users,id',
        ]);
        $data['requester_jabatan_id'] = $this->resolveJabatanId($data['requester_jabatan']);
        $data['approver_jabatan_id'] = $this->resolveJabatanId($data['approver_jabatan']);
        $mapping->update($data);
        return redirect(route('approval-mapping.index') . '?updated=1')->with('success', 'Mapping approver 1 berhasil diperbarui.')->withFragment('mapping-list');
    }

    /* Cari jabatan_id (dari HRIS) untuk nama jabatan: dari user yang memegang jabatan tsb. */
    private function resolveJabatanId($nama)
    {
        $nama = trim($nama ?? '');
        if ($nama === '') return null;

        $user = User::whereRaw('LOWER(jabatan) = ?', [strtolower($nama)])->first();
        return $user && $user->jabatan_id ? $user->jabatan_id : null;
    }

    /* Simpan user tahap 2 (Manajer Umum) ke settings. */
    public function saveStage2User(Request $request)
    {
        $this->ensureIT();
        $data = $request->validate([
            'stage2_user_id' => 'nullable|exists:users,id',
        ]);
        Setting::set('stage2_user_id', $data['stage2_user_id'] ?? null);
        return redirect(route('approval-mapping.index') . '?saved=1')->with('success', 'Mapping approver 2 berhasil disimpan.');
    }

    public function destroy($id)
    {
        $this->ensureIT();
        ApprovalMapping::findOrFail($id)->delete();
        return redirect(route('approval-mapping.index') . '?deleted=1')->with('success', 'Mapping approver 1 berhasil dihapus.');
    }
}
