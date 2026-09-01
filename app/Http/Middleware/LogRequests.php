<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Catat setiap request yang masuk ke storage/logs/requests.log
     * Format: [waktu] METHOD URL STATUS IP — USER_AGENT (durasi)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);
        $status = $response->getStatusCode();
        $level = $status >= 500 ? 'error' : ($status >= 400 ? 'warning' : 'info');

        // IP asli pengunjung: Cloudflare kirim header CF-Connecting-IP
        // (karena request masuk lewat tunnel lokal, IP bawaan selalu 127.0.0.1)
        $ip = $request->header('CF-Connecting-IP') ?: $request->ip();

        Log::channel('requests')->$level(sprintf(
            '%s %s %d %s — %s (%sms)',
            $request->method(),
            $request->fullUrl(),
            $status,
            $ip,
            $request->userAgent() ?: '-',
            $duration
        ));

        return $response;
    }
}
