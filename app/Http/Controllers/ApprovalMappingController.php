<?php

namespace App\Http\Controllers;

use App\Models\ApprovalMapping;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalMappingController extends Controller
{
    private function ensureIT()
    {
        $user = auth()->user();
        if (!$user || strtolower(trim($user->unit ?? '')) != 'teknologi dan informasi') {
            abort(403, 'Hanya user IT yang dapat mengakses panel ini.');
        }
    }

    public function index()
    {
        $this->ensureIT();
        $mappings = ApprovalMapping::orderBy('requester_jabatan')->get();
        $stage2 = config('approvals.stage2_jabatan');
        $jabatanList = collect(config('units.utw'))
            ->concat(Jabatan::orderBy('nama')->pluck('nama'))
            ->concat(User::whereNotNull('jabatan')->pluck('jabatan'))
            ->concat($mappings->pluck('requester_jabatan'))
            ->concat($mappings->pluck('approver_jabatan'))
            ->filter()
            ->unique()
            ->values();
        return view('pages.approval-mapping.index', compact('mappings', 'stage2', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $this->ensureIT();
        $data = $request->validate([
            'requester_jabatan' => 'required|string|max:100|unique:approval_mappings,requester_jabatan',
            'approver_jabatan'  => 'required|string|max:100',
        ]);
        $data['requester_jabatan_id'] = $this->resolveJabatanId($data['requester_jabatan']);
        $data['approver_jabatan_id'] = $this->resolveJabatanId($data['approver_jabatan']);
        ApprovalMapping::create($data);
        return redirect()->route('approval-mapping.index')->with('success', 'Mapping atasan langsung berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $this->ensureIT();
        $mapping = ApprovalMapping::findOrFail($id);
        $data = $request->validate([
            'requester_jabatan' => 'required|string|max:100|unique:approval_mappings,requester_jabatan,' . $mapping->id,
            'approver_jabatan'  => 'required|string|max:100',
        ]);
        $data['requester_jabatan_id'] = $this->resolveJabatanId($data['requester_jabatan']);
        $data['approver_jabatan_id'] = $this->resolveJabatanId($data['approver_jabatan']);
        $mapping->update($data);
        return redirect()->route('approval-mapping.index')->with('success', 'Mapping atasan langsung berhasil diperbarui.');
    }

    /* Cari jabatan_id (dari HRIS) untuk nama jabatan: master dulu, lalu users. */
    private function resolveJabatanId($nama)
    {
        $nama = trim($nama ?? '');
        if ($nama === '') return null;

        $jb = Jabatan::whereRaw('LOWER(nama) = ?', [strtolower($nama)])->first();
        if ($jb) return $jb->id;

        $user = User::whereRaw('LOWER(jabatan) = ?', [strtolower($nama)])->first();
        if ($user && $user->jabatan_id) return $user->jabatan_id;

        return null;
    }

    public function destroy($id)
    {
        $this->ensureIT();
        ApprovalMapping::findOrFail($id)->delete();
        return redirect()->route('approval-mapping.index')->with('success', 'Mapping atasan langsung berhasil dihapus.');
    }
}