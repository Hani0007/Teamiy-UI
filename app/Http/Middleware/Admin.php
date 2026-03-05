<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelper;
use App\Models\Role;
use Closure;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Middleware\PermissionMiddleware;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        // 🔹 First, check admin guard
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            if (!$user || !$user->can($permission)) {
                throw UnauthorizedException::forPermissions([$permission]);
            }
            return $next($request);
        }

        // 🔹 Then, check default guard (web)
        if (Auth::check()) {
            $user = Auth::user();
            if (
                !$user ||
                !in_array($user->role?->slug, \App\Helpers\AppHelper::getBackendLoginAuthorizedRole()) ||
                !$user->can($permission)
            ) {
                throw UnauthorizedException::forPermissions([$permission]);
            }
            return $next($request);
        }

        // 🔹 If no guard is authenticated
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}



