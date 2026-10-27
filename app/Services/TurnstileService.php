<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TurnstileService
{
    /**
     * Проверяет токен Cloudflare Turnstile через Siteverify.
     * Требует success, ожидаемое действие и разрешенный hostname.
     */
    public function verify(?string $token, ?string $remoteIp): bool
    {
        $secret = (string) config('services.turnstile.secret');
        $expectedAction = (string) config('services.turnstile.action', 'order_driver');
        $hostnames = collect(explode(',', (string) config('services.turnstile.hostnames')))
            ->map(fn ($host) => trim($host))
            ->filter()
            ->values();

        if ($secret === '' || ! is_string($token) || $token === '' || strlen($token) > 2048 || $hostnames->isEmpty()) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $remoteIp,
                ]);
        } catch (\Throwable) {
            return false;
        }

        if (! $response->ok()) {
            return false;
        }

        $result = $response->json();

        return ($result['success'] ?? false) === true
            && ($result['action'] ?? null) === $expectedAction
            && $hostnames->contains($result['hostname'] ?? null);
    }
}
