<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null, $action = null): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Anda tidak memiliki akses');
        }

        // Jika Superadmin, boleh semua
        if ($user->hasRole('Superadmin')) {
            return $next($request);
        }

        // Jika User biasa, hanya boleh view
        if ($user->hasRole('User')) {
            if ($action !== 'view') {
                abort(403, 'User hanya bisa melihat data');
            }
            return $next($request);
        }

        // Jika Admin
        if ($user->hasRole('Admin')) {
            // Admin hanya bisa action tertentu
            $allowed = ['view', 'create', 'update', 'delete'];
            if ($action && !in_array($action, $allowed)) {
                abort(403, 'Admin tidak boleh melakukan aksi ini');
            }
            return $next($request);
        }

        // Default block
        abort(403, 'Akses ditolak');
    }
}
