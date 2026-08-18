<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $response->headers->set(
                'Cache-Control',
                'public, max-age=60, s-maxage=300, stale-while-revalidate=60'
            );
            $response->headers->remove('Pragma');
            $response->headers->set('Vary', 'Accept-Encoding');
        }

        return $response;
    }
}
