<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['name', 'nik', 'username', 'status_karyawan', 'created_at'];

            $query = User::query();

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhere('status_karyawan', 'like', "%{$search}%");
                });
            }

            $recordsTotal = User::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $orderColumn = $columns[$request->order[0]['column']] ?? 'name';
                $orderDir = $request->order[0]['dir'] ?? 'asc';
                $query->orderBy($orderColumn, $orderDir);
            } else {
                $query->orderBy('name', 'asc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $avatarClasses = [
                'um-avatar--teal',
                'um-avatar--blue',
                'um-avatar--purple',
                'um-avatar--pink',
                'um-avatar--amber',
            ];

            $data = [];

            foreach ($records as $item) {
                $nama = ucwords(str_replace('.', ' ', $item->name ?? 'User'));
                $initials = collect(explode(' ', $nama))
                    ->take(2)
                    ->map(fn($w) => strtoupper($w[0]))
                    ->join('');
                $avClass = $avatarClasses[abs(crc32($nama)) % count($avatarClasses)];

                $data[] = [
                    'id' => $item->id,
                    'name' => $nama,
                    'initials' => $initials,
                    'avatar_class' => $avClass,
                    'jabatan' => $item->jabatan ?? 'Tidak ada jabatan',
                    'nik' => $item->nik ?? '-',
                    'username' => $item->username ?? '-',
                    'status_karyawan' => $item->status_karyawan ?? '-',
                    'user_id' => '#' . $item->id,
                    'created_at_timestamp' => Carbon::parse($item->created_at)->timestamp,
                    'created_at_formatted' => Carbon::parse($item->created_at)->format('d M Y'),
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        $userCount = User::count();

        return view('layouts.users.index', compact('userCount'));
    }
}