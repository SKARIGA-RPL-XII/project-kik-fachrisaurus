<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // kalau belum login
        if (!$user) {
            return redirect()->route('login');
        }

        // kalau role user tidak sesuai yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
