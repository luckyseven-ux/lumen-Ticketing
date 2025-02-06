<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        // Ambil pengguna yang sedang login
        $user = auth()->user();

        // Periksa apakah role pengguna sesuai
        if ($user && $user->role === $role) {
            return $next($request); // Lanjutkan request
        }

        // Jika tidak sesuai, kembalikan error 403
        return response()->json([
            'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.',
        ], 403);
    }
}