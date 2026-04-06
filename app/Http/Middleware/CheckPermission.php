<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $menu, $action)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if (!PermissionHelper::canAccess($menu, $action)) {
            $moduleName = str_replace('_', ' ', $menu);
            abort(403, 'Tidak punya akses untuk ' . $action . ' ' . ucfirst($moduleName));
        }

        return $next($request);
    }

    
}