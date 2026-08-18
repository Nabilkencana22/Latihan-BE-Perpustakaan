<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $allowedRoles = !empty($roles) ? $roles : ['admin', 'petugas'];

        if (! $request->user() || ! in_array($request->user()->role, $allowedRoles)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengakses operasi ini!',
            ], 403);
        }
        return $next($request);
    }
}
