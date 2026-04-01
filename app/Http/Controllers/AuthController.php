<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
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

        $remember = $request->has('remember');

        $localUser = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        // 🔹 Login lokal
        if ($localUser && Hash::check($request->password, $localUser->password)) {
            Auth::login($localUser, $remember);
            return redirect()->intended('/dashboard');
        }

        // 🔹 Login ke HRIS API
        try {
            $response = Http::timeout(5)
                ->withoutVerifying()
                ->post('https://hris.rs-hamori.co.id/api/login', [
                    'email' => $request->username,
                    'password' => $request->password,
                ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'username' => 'Server HRIS tidak bisa diakses, coba lagi nanti.'
            ]);
        }

        if (!$response->successful() || !$response->json('success')) {
            return back()->withErrors([
                'username' => 'Username / Email atau password salah.'
            ])->onlyInput('username');
        }

        $result  = $response->json();
        $apiUser = $result['data']['user'];
        $token   = $result['data']['access_token'];

        $unit = data_get($apiUser, 'karyawan.unit.name');

        $user = User::updateOrCreate(
            ['email' => $apiUser['email']],
            [
                'name'       => $apiUser['name'],
                'username'   => $apiUser['name'],
                'password'   => bcrypt($request->password),
                'unit'  => $unit,
            ]
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
                    ->post('https://hris.rs-hamori.co.id/api/logout');
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
