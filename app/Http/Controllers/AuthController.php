<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
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

        $key = Str::lower($request->username) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam $seconds detik."
            ]);
        }

        $remember = $request->has('remember');

        $localUser = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($localUser && Hash::check($request->password, $localUser->password)) {
            RateLimiter::clear($key);

            Auth::login($localUser, $remember);

            return redirect()->intended('/dashboard');
        }

        $response = Http::post('http://192.168.10.20:8084/api/login', [
            'email' => $request->username,
            'password' => $request->password,
        ]);

        if (!$response->successful() || !$response->json('success')) {
            RateLimiter::hit($key, 60);

            return back()->withErrors([
                'username' => 'Username / Email atau password salah.'
            ])->onlyInput('username');
        }

        RateLimiter::clear($key);

        $result  = $response->json();
        $apiUser = $result['data']['user'];
        $token   = $result['data']['access_token'];

        $unitId = data_get($apiUser, 'karyawan.unit.id');

        $user = User::updateOrCreate(
            ['email' => $apiUser['email']],
            [
                'name'     => $apiUser['name'],
                'username' => $apiUser['name'],
                'password' => bcrypt($request->password),
                'unit_id'  => $unitId,
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
                    ->post('http://192.168.10.20:8084/api/logout');
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
