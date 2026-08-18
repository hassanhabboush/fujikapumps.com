<?php

namespace App\Http\Middleware;

use App\Support\CatalogCache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CachePublicHtml
{
    private const TTL_SECONDS = 180;

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') || app()->environment('testing')) {
            return $next($request);
        }

        $key = $this->key($request);
        $cached = Cache::get($key);

        if (is_string($cached) && $cached !== '') {
            return response($cached, 200, [
                'Content-Type'  => 'text/html; charset=UTF-8',
                'Cache-Control' => 'public, max-age=60, s-maxage=300, stale-while-revalidate=60',
                'X-Html-Cache'  => 'HIT',
            ]);
        }

        $response = $next($request);

        if (
            $response->getStatusCode() === 200
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html')
        ) {
            Cache::put($key, $response->getContent(), self::TTL_SECONDS);
            $response->headers->set('X-Html-Cache', 'MISS');
        }

        return $response;
    }

    private function key(Request $request): string
    {
        return 'public-html.'.CatalogCache::version().'.'.sha1(
            $request->getHost().'|'.$request->getRequestUri()
        );
    }
}
