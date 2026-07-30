<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'errors' => ['auth' => ['Anda belum terautentikasi.']],
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (! in_array($user->role, $roles, true) && $user->role !== 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hak akses ditolak.',
                'errors' => ['access' => ['Anda tidak memiliki wewenang untuk mengakses sumber daya ini.']],
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
