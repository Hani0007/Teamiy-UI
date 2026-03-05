<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTrialExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs([
            'login',
            'login.submit',
            'logout',
            'admin.subscription.*',
        ])) {
            return $next($request);
        }

        $user = auth('admin')->user() ?? auth('web')->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $trialEndDate   = $user->created_at->copy()->addDays(15);
        $isTrialExpired = now()->greaterThanOrEqualTo($trialEndDate);

        $roleName = $user->getRoleNames()->first();

        if ($isTrialExpired && $roleName !== 'super-admin') {
            abort(403, 'Trial expired. Please contact support.');
        }

        if ($isTrialExpired && $user->plan_id == 1 && $roleName === 'super-admin' && !$request->routeIs('admin.subscription.plans') )
        {
            return redirect()->route('admin.subscription.plans');
        }

        return $next($request);
    }
}
