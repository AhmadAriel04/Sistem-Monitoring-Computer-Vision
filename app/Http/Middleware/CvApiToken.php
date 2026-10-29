<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CvApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expectedToken = (string) config('services.cv.token');
        $providedToken = (string) $request->header('X-CV-Token');

        if ($expectedToken === '') {
            return response()->json([
                'success' => false,
                'message' => 'CV API token belum dikonfigurasi pada server.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Token tidak dikirim
        |--------------------------------------------------------------------------
        */

        if ($providedToken === '') {
            return response()->json([
                'success' => false,
                'message' => 'X-CV-Token wajib dikirim.',
            ], 401);
        }


        if (!hash_equals($expectedToken, $providedToken)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized CV Request',
            ], 401);
        }

        return $next($request);
    }
}