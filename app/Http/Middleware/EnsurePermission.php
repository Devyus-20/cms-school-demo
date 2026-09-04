<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Pastikan user yang login memiliki salah satu permission yang diminta.
     * Contoh pemakaian di route: ->middleware('permission:Kelola User')
     * Bisa juga beberapa permission sekaligus (lolos jika punya salah satu):
     * ->middleware('permission:Kelola User,Kelola Settings')
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();

        if (! $user || ! $user->role) {
            abort(403, 'Akun Anda belum memiliki role yang valid. Hubungi Administrator.');
        }

        $required = array_map('trim', explode(',', $permissions));

        $ownedPermissions = $user->role->permissions()->pluck('name')->all();

        $allowed = collect($required)->intersect($ownedPermissions)->isNotEmpty();

        if (! $allowed) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
