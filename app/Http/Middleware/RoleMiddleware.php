<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }
        // Mendukung kolom `role` string maupun relasi roles (spatie) secara longgar.
        $userRoles = [];
        if (isset($user->role) && is_string($user->role)) {
            $userRoles[] = $user->role;
        }
        if (method_exists($user, 'getRoleNames')) {
            try {
                foreach ($user->getRoleNames() as $r) {
                    $userRoles[] = (string) $r;
                }
            } catch (\Throwable $e) {
            }
        }
        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return $next($request);
            }
        }
        abort(403, 'Akses ditolak untuk peran Anda.');
    }
}
