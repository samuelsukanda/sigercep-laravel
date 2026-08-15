<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Jabatan;
use App\Models\ApprovalMapping;

class AuthController extends Controller
{
    protected string $hrisBaseUrl = 'https://hris.rs-hamori.co.id/api';

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        $usernameInput = trim($request->username);

        if (!str_contains($usernameInput, '@')) {
            $usernameInput .= '@rs-hamori.co.id';
        }

        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->post($this->hrisBaseUrl . '/login', [
                    'email' => $usernameInput,
                    'password' => $request->password,
                ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'username' => 'Server HRIS sedang sibuk, coba lagi nanti.'
            ])->onlyInput('username');
        }

        if (!$response->successful() || !$response->json('success')) {
            return back()->withErrors([
                'username' => 'Username / Email atau password salah.'
            ])->onlyInput('username');
        }

        $result  = $response->json();
        $apiUser = $result['data']['user'];
        $token   = $result['data']['access_token'];

        $statusKaryawan = data_get($apiUser, 'karyawan.status');

        if ($statusKaryawan !== 'active') {
            return back()->withErrors([
                'username' => 'Karyawan sudah resign atau akun tidak aktif.'
            ])->onlyInput('username');
        }

        $userData = [
            'name'            => data_get($apiUser, 'user.name', $apiUser['name']),
            'username'        => $apiUser['email'],
            'email'           => $apiUser['email'],
            'nik'             => data_get($apiUser, 'karyawan.nik'),
            'unit'            => data_get($apiUser, 'karyawan.unit.name'),
            'unit_id'         => data_get($apiUser, 'karyawan.unit.id'),
            'jabatan'         => data_get($apiUser, 'karyawan.jabatan.name'),
            'jabatan_id'      => data_get($apiUser, 'karyawan.jabatan.id'),
            'status_karyawan' => $statusKaryawan,
        ];

        $this->syncJabatanMaster($apiUser);

        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            $userData
        );

        // Backfill id ke mapping lama yang cocok lewat teks (SPV/Supervisor dll ter-linking otomatis bila sama persis)
        $this->syncMappingIds($user);

        session([
            'hris_token' => $token,
            'hris_user'  => $apiUser,
        ]);

        Auth::login($user, $remember);

        return redirect()->intended('/dashboard');
    }

    public function devLoginPage()
    {
        if (!app()->environment('local')) abort(404);
        return view('dev-login', ['users' => User::orderBy('id')->get()]);
    }

    public function devLogin($id)
    {
        if (!app()->environment('local')) abort(404);
        Auth::login(User::findOrFail($id));
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        $token = session('hris_token');

        if ($token) {
            try {
                Http::withToken($token)
                    ->withoutVerifying()
                    ->post($this->hrisBaseUrl . '/logout');
            } catch (\Exception $e) {
            }
        }

        session()->forget(['hris_token', 'hris_user']);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function syncJabatanMaster($apiUser)
    {
        $jb = data_get($apiUser, 'karyawan.jabatan');

        if (is_array($jb) && !empty($jb['id'])) {
            Jabatan::updateOrCreate(['id' => $jb['id']], [
                'nama'          => $jb['name'] ?? null,
                'manager_id'    => $jb['manager_id'] ?? null,
                'level_approve' => $jb['level_approve'] ?? null,
            ]);
        }
    }

    private function syncMappingIds(User $user)
    {
        if (!$user->jabatan_id || !$user->jabatan) return;

        $jabatan = strtolower(trim($user->jabatan));

        ApprovalMapping::whereRaw('LOWER(requester_jabatan) = ?', [$jabatan])
            ->whereNull('requester_jabatan_id')
            ->update(['requester_jabatan_id' => $user->jabatan_id]);

        ApprovalMapping::whereRaw('LOWER(approver_jabatan) = ?', [$jabatan])
            ->whereNull('approver_jabatan_id')
            ->update(['approver_jabatan_id' => $user->jabatan_id]);
    }
}
