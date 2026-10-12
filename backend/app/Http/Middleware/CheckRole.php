<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();

        if ($user->status !== 'active') {
            return response()->json(['status' => 'error', 'message' => 'Akun dinonaktifkan'], 403);
        }

        if ($role === 'admin' && $user->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak (khusus admin)'], 403);
        }

        if ($role === 'seller' && !$user->seller) {
            return response()->json(['status' => 'error', 'message' => 'Akses ditolak (khusus seller)'], 403);
        }

        return $next($request);
    }
}