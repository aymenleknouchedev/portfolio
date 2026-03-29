<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        $ipHash = hash('sha256', $request->ip() . config('app.key'));

        SiteVisit::firstOrCreate([
            'ip_hash' => $ipHash,
            'visited_date' => now()->toDateString(),
        ]);

        return $next($request);
    }
}
