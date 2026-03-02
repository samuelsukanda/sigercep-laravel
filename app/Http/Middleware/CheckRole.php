<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === 'user' && $user->unit_id != 8) {
            abort(403, 'Anda tidak memiliki akses ke menu laporan.');
        }

        return $next($request);
    }
}
