<?php

namespace App\Services;

class DeviceParser
{
    /**
     * Определяет тип устройства, модель, ОС и браузер по User-Agent.
     * Client Hints (navigator.userAgentData) позволяют уточнить модель Android-телефона.
     *
     * @param  array<string, string>|null  $clientHints
     * @return array{device_type: string, device_brand_model: string, os: string, browser: string, is_bot: bool}
     */
    public static function parse(string $userAgent, ?array $clientHints = null): array
    {
        $ua = $userAgent;

        $isBot = (bool) preg_match(
            '/bot|crawl|spider|slurp|yandex|bingpreview|facebookexternalhit|whatsapp|telegram|preview|headless|lighthouse|pingdom|uptimerobot/i',
            $ua
        );

        $os = 'Неизвестно';
        if (preg_match('/Windows NT 10/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Windows/i', $ua)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac OS X/i', $ua) && ! preg_match('/iPhone|iPad/i', $ua)) {
            $os = 'macOS';
        } elseif (preg_match('/iPhone|iPad|iPod/i', $ua)) {
            $os = 'iOS';
        } elseif (preg_match('/Android/i', $ua)) {
            $os = 'Android';
        } elseif (preg_match('/Linux/i', $ua)) {
            $os = 'Linux';
        }

        $browser = 'Другой';
        if (preg_match('/YaBrowser|Yandex/i', $ua)) {
            $browser = 'Yandex';
        } elseif (preg_match('/Edg\//i', $ua)) {
            $browser = 'Edge';
        } elseif (preg_match('/OPR\/|Opera/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/SamsungBrowser/i', $ua)) {
            $browser = 'Samsung Internet';
        } elseif (preg_match('/Firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Chrome|CriOS/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Safari/i', $ua)) {
            $browser = 'Safari';
        }

        $deviceType = 'desktop';
        $model = 'Компьютер';

        if (preg_match('/iPad/i', $ua)) {
            $deviceType = 'tablet';
            $model = 'Apple iPad';
        } elseif (preg_match('/iPhone/i', $ua)) {
            $deviceType = 'mobile';
            $model = 'Apple iPhone';
        } elseif (preg_match('/Android/i', $ua)) {
            $deviceType = preg_match('/Mobile/i', $ua) ? 'mobile' : 'tablet';
            $model = self::androidBrand($ua);
        } elseif (preg_match('/Macintosh/i', $ua)) {
            $model = 'Mac';
        } elseif (preg_match('/Windows/i', $ua)) {
            $model = 'Windows PC';
        } elseif (preg_match('/Linux/i', $ua)) {
            $model = 'Linux PC';
        }

        if ($clientHints) {
            $hintModel = trim((string) ($clientHints['model'] ?? ''));
            $hintPlatform = trim((string) ($clientHints['platform'] ?? ''));
            $hintMobile = filter_var($clientHints['mobile'] ?? false, FILTER_VALIDATE_BOOLEAN);

            if ($hintModel !== '') {
                $model = mb_substr($hintModel, 0, 120);
                $deviceType = $hintMobile ? 'mobile' : 'tablet';
            }
            if ($hintPlatform !== '') {
                $os = match (strtolower($hintPlatform)) {
                    'android' => 'Android',
                    'ios' => 'iOS',
                    'windows' => 'Windows',
                    'macos', 'mac os' => 'macOS',
                    'linux' => 'Linux',
                    default => $os,
                };
            }
        }

        return [
            'device_type' => $deviceType,
            'device_brand_model' => $model,
            'os' => $os,
            'browser' => $browser,
            'is_bot' => $isBot,
        ];
    }

    private static function androidBrand(string $ua): string
    {
        $brands = [
            'Samsung Galaxy' => '/SM-[A-Z0-9]+|Samsung|Galaxy/i',
            'Xiaomi / Redmi / POCO' => '/Redmi|POCO|Mi |Xiaomi/i',
            'Huawei / Honor' => '/HUAWEI|Honor|HMSCore/i',
            'Google Pixel' => '/Pixel/i',
            'OnePlus' => '/OnePlus/i',
            'OPPO / Realme' => '/OPPO|Realme|RMX/i',
            'Vivo' => '/vivo/i',
            'Nokia' => '/Nokia/i',
        ];

        foreach ($brands as $label => $pattern) {
            if (preg_match($pattern, $ua)) {
                return $label;
            }
        }

        return 'Android';
    }
}
