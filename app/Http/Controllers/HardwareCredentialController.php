<?php

namespace App\Http\Controllers;

use App\Models\HardwareCredential;
use App\Models\MasterKomputer;
use App\Models\MasterMiniPc;
use Illuminate\Http\Request;

class HardwareCredentialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:hardware,read')->only(['index', 'getData', 'getItems']);
        $this->middleware('permission:hardware,create')->only(['simpan']);
        $this->middleware('permission:hardware,update')->only(['simpan']);
        $this->middleware('permission:hardware,delete')->only(['hapus']);
    }

    public function index()
    {
        $daftarPc = [];
        MasterKomputer::orderBy('nama_pc')->get()->each(function ($pc) use (&$daftarPc) {
            $daftarPc[] = [
                'nama_pc' => $pc->nama_pc,
                'ip'      => $pc->ip,
                'unit'    => $pc->unit,
                'lantai'  => $pc->lantai,
                'jenis'   => 'Komputer',
            ];
        });
        MasterMiniPc::orderBy('nama_pc')->get()->each(function ($pc) use (&$daftarPc) {
            $daftarPc[] = [
                'nama_pc' => $pc->nama_pc,
                'ip'      => $pc->ip,
                'unit'    => '-',
                'lantai'  => $pc->lantai,
                'jenis'   => 'Mini PC & Laptop',
            ];
        });

        return view('pages.hardware.credential', [
            'daftarPc' => $daftarPc,
        ]);
    }

    public function getData(Request $request)
    {
        $query = HardwareCredential::query();

        if ($request->filled('cari')) {
            $cari = trim($request->cari);
            $query->where(function ($q) use ($cari) {
                $q->where('nama_pc', 'like', "%{$cari}%")
                    ->orWhere('ip', 'like', "%{$cari}%")
                    ->orWhere('unit', 'like', "%{$cari}%");
            });
        }

        $records = $query
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        $data = [];
        foreach ($records as $record) {
            $data[] = [
                'id'                  => $record->id,
                'nama_pc'             => $record->nama_pc,
                'ip'                  => $record->ip,
                'unit'                => $record->unit,
                'lantai'              => $record->lantai,
                'updated_at_formatted' => $record->updated_at ? $record->updated_at->translatedFormat('d M Y') : '-',
                'items'               => array_values($record->items ?? []),
            ];
        }

        return response()->json(['data' => $data]);
    }

    public function getItems($id)
    {
        $record = HardwareCredential::findOrFail($id);

        return response()->json([
            'record' => [
                'id'      => $record->id,
                'nama_pc' => $record->nama_pc,
                'ip'      => $record->ip,
                'unit'    => $record->unit,
                'lantai'  => $record->lantai,
                'jenis'   => ($record->unit !== null && $record->unit !== '-' && $record->unit !== '') ? 'Komputer' : 'Mini PC & Laptop',
            ],
            'items' => array_values($record->items ?? []),
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'id'               => 'nullable|integer',
            'nama_pc'          => 'required|string|max:255',
            'rows'             => 'array',
            'rows.*.nama'      => 'nullable|string',
            'rows.*.username'  => 'nullable|string',
            'rows.*.password'  => 'nullable|string',
            'rows.*.notes'     => 'nullable|string',
        ]);

        $items = [];
        foreach (($request->rows ?? []) as $row) {
            $username = trim($row['username'] ?? '');
            $password = trim($row['password'] ?? '');
            $nama     = trim($row['nama'] ?? '');

            if ($username === '' && $password === '' && $nama === '') continue;

            $items[] = [
                'nama'     => $nama !== '' ? $nama : null,
                'username' => $username !== '' ? $username : null,
                'password' => $password !== '' ? $password : null,
                'notes'    => trim($row['notes'] ?? '') !== '' ? trim($row['notes'] ?? '') : null,
            ];
        }

        $dataHeader = [
            'nama_pc' => $request->nama_pc,
            'ip'      => $request->ip ?: null,
            'unit'    => $request->unit ?: null,
            'lantai'  => $request->lantai ?: null,
            'items'   => $items,
        ];

        if ($request->filled('id')) {
            $record = HardwareCredential::findOrFail($request->id);
            $record->update($dataHeader);
        } else {
            $record = HardwareCredential::create($dataHeader);
        }

        return response()->json(['success' => true, 'id' => $record->id]);
    }

    public function hapus($id)
    {
        $record = HardwareCredential::findOrFail($id);
        $record->delete();

        return response()->json(['success' => true]);
    }
}