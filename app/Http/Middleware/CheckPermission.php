<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermissionHelper;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $module, $action)
    {
        if (!\App\Helpers\PermissionHelper::canAccess($module, $action)) {
            $moduleName = str_replace('_', ' ', $module);
            abort(403, 'Anda tidak punya izin untuk ' . $action . ' ' . ucfirst($moduleName));
        }

        return $next($request);
    }
}
