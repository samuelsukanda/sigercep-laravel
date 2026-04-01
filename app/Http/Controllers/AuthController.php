<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
    protected string $hrisBaseUrl = 'http://192.168.10.102:8000/api';

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
            'jabatan'         => data_get($apiUser, 'karyawan.jabatan.name'),
            'status_karyawan' => $statusKaryawan,
        ];

        $user = User::updateOrCreate(
            ['email' => $userData['email']],
            $userData
        );

        session([
            'hris_token' => $token,
            'hris_user'  => $apiUser,
        ]);

        Auth::login($user, $remember);

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
}
