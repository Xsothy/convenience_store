<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureTheSameDevice
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $deviceId = $request->cookie('device_id');
        if (! $deviceId) {
            $deviceId = (string) Str::uuid7();
            cookie()->queue(cookie('device_id', $deviceId, 60 * 24 * 365));
        }
        session(['device_id' => $deviceId]);

        return $next($request);
    }
}
