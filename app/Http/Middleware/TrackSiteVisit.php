<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use App\Services\DeviceParser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || ! $response->isSuccessful()) {
            return $response;
        }

        $userAgent = (string) $request->userAgent();
        $ip = $this->realIp($request);
        $parsed = DeviceParser::parse($userAgent);

        if ($parsed['is_bot'] || $userAgent === '') {
            return $response;
        }

        $locale = $request->route('locale') ?: 'az';
        $today = now()->toDateString();

        SiteVisit::create([
            'visitor_hash' => hash('sha256', $ip.'|'.$userAgent.'|'.$today),
            'ip_address' => $ip,
            'country_code' => strtoupper(substr((string) $request->header('CF-IPCountry', ''), 0, 8)) ?: null,
            'device_type' => $parsed['device_type'],
            'device_brand_model' => $parsed['device_brand_model'],
            'os' => $parsed['os'],
            'browser' => $parsed['browser'],
            'locale' => $locale,
            'path' => '/'.ltrim($request->path(), '/'),
            'referer' => mb_substr((string) $request->headers->get('referer', ''), 0, 255) ?: null,
            'is_bot' => false,
            'visited_date' => $today,
        ]);

        return $response;
    }

    /**
     * Реальный IP клиента за Cloudflare: CF-Connecting-IP приоритетнее прокси-заголовков.
     */
    public static function realIp(Request $request): string
    {
        $cfIp = $request->header('CF-Connecting-IP');

        if (is_string($cfIp) && filter_var($cfIp, FILTER_VALIDATE_IP)) {
            return $cfIp;
        }

        return $request->ip() ?? '0.0.0.0';
    }
}
