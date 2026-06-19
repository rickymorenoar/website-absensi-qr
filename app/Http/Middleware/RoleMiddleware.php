<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Ubah $role jadi ...$roles biar bisa nampung banyak role (array)
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect('/');
        }

        $user = auth()->user();

        // 2. Cek apakah role user ada di dalam daftar $roles yang diizinkan
        // in_array akan ngecek: apakah role user ("guru") ada di dalam array ["guru", "admin"]?
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // 3. Kalau tidak cocok, tolak akses
        abort(403, 'Akses Ditolak. Halaman ini bukan untuk role Anda.');
    }
}