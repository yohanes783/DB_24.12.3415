<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    if (auth()->check() && auth()->user()->role === 'superadmin') {
        return $next($request);
    }

    abort(403, 'Halaman ini hanya dapat diakses oleh Superadmin.');
    }
}
