<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Mencegah browser menyimpan cache halaman yang membutuhkan autentikasi (dashboard & admin).
     * Dengan ini, jika link dipaste atau tombol Back ditekan setelah logout,
     * browser tidak akan menampilkan cache halaman dan akan memaksa redirect ke login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (method_exists($response, 'header')) {
            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                             ->header('Pragma', 'no-cache')
                             ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }

        if (isset($response->headers)) {
            $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
        }

        return $response;
    }
}
