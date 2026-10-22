<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach (['X-Content-Type-Options' => 'nosniff', 'X-Frame-Options' => 'DENY', 'Referrer-Policy' => 'strict-origin-when-cross-origin', 'Permissions-Policy' => 'camera=(), microphone=(), geolocation=()'] as $k => $v) {
            $response->headers->set($k, $v);
        }

        $isLocal = config('app.env') === 'local';
        $viteHttpSources = $isLocal
            ? ' http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173'
            : '';
        $viteWebSocketSources = $isLocal
            ? ' ws://localhost:5173 ws://127.0.0.1:5173 ws://[::1]:5173'
            : '';

        $response->headers->set('Content-Security-Policy', implode('; ', [
            "default-src 'self'",
            "img-src 'self' data: https://res.cloudinary.com",
            "media-src 'self' https://res.cloudinary.com",
            "style-src 'self' 'unsafe-inline'{$viteHttpSources}",
            "script-src 'self'{$viteHttpSources}",
            "connect-src 'self'{$viteHttpSources}{$viteWebSocketSources}",
            "font-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]));

        return $response;
    }
}
