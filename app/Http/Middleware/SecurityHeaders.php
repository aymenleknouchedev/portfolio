<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Adds a baseline of HTTP security headers to every response.
     * Avoids a strict CSP because the public site relies on inline scripts
     * (Alpine x-data, AOS, TinyMCE), so a CSP would break the UI without
     * extensive refactoring. The headers below cover the highest-impact
     * OWASP recommendations (XSS, clickjacking, MIME sniffing, referrer leak).
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = [
            'X-Content-Type-Options'  => 'nosniff',
            'X-Frame-Options'         => 'SAMEORIGIN',
            'Referrer-Policy'         => 'strict-origin-when-cross-origin',
            'X-XSS-Protection'        => '0',
            'Permissions-Policy'      => 'camera=(), microphone=(), geolocation=(), payment=(self)',
            'Cross-Origin-Opener-Policy'   => 'same-origin-allow-popups',
            'Cross-Origin-Resource-Policy' => 'same-site',
        ];

        // HSTS only over HTTPS to avoid breaking local HTTP development
        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $name => $value) {
            if (!$response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }
}
