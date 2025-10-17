<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        if (!PermissionHelper::canAccess($module, $action)) {
            abort(403, 'Anda tidak punya izin untuk ' . $action . ' ' . $module);
        }

        return $next($request);
    }
}
