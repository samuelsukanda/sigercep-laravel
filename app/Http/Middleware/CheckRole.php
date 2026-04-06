<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PermissionHelper;

class CheckRole
{
    public function handle(Request $request, Closure $next, $menu, $action)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!PermissionHelper::canAccess($menu, $action)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}