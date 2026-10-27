<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TrackSiteVisit;
use App\Mail\OrderReceived;
use App\Models\OrderRequest;
use App\Services\DeviceParser;
use App\Services\TurnstileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store(Request $request, TurnstileService $turnstile): JsonResponse
    {
        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'phone' => ['required', 'string', 'min:7', 'max:30'],
            'pickup_address' => ['nullable', 'string', 'max:255'],
            'destination_address' => ['nullable', 'string', 'max:255'],
            'tariff_zone' => ['nullable', 'string', 'in:'.implode(',', OrderRequest::TARIFF_ZONES)],
            'car_type' => ['nullable', 'string', 'max:120'],
            'comment' => ['nullable', 'string', 'max:1000'],
            'locale' => ['nullable', 'string', 'in:az,ru,tr,en'],
            'cf-turnstile-response' => ['required', 'string', 'max:2048'],
            'device_model' => ['nullable', 'string', 'max:120'],
            'device_platform' => ['nullable', 'string', 'max:60'],
            'device_mobile' => ['nullable', 'boolean'],
        ]);

        $ip = TrackSiteVisit::realIp($request);

        if (! $turnstile->verify($data['cf-turnstile-response'], $ip)) {
            return response()->json([
                'ok' => false,
                'message' => __('site.order.captcha_failed'),
            ], 422);
        }

        $parsed = DeviceParser::parse((string) $request->userAgent(), [
            'model' => $data['device_model'] ?? '',
            'platform' => $data['device_platform'] ?? '',
            'mobile' => $data['device_mobile'] ?? false,
        ]);

        $order = OrderRequest::create([
            'name' => $data['name'] ?? null,
            'phone' => $data['phone'],
            'pickup_address' => $data['pickup_address'] ?? null,
            'destination_address' => $data['destination_address'] ?? null,
            'tariff_zone' => $data['tariff_zone'] ?? null,
            'car_type' => $data['car_type'] ?? null,
            'comment' => $data['comment'] ?? null,
            'locale' => $data['locale'] ?? 'az',
            'status' => 'new',
            'ip_address' => $ip,
            'country_code' => strtoupper(substr((string) $request->header('CF-IPCountry', ''), 0, 8)) ?: null,
            'device_name' => trim($parsed['device_brand_model'].' · '.$parsed['os'].' · '.$parsed['browser']),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 1000),
        ]);

        $adminEmail = (string) config('services.admin_notification_email');
        if ($adminEmail !== '') {
            try {
                Mail::to($adminEmail)->send(new OrderReceived($order));
            } catch (\Throwable) {
                // Заявка уже сохранена: сбой почты не должен ломать ответ клиенту.
            }
        }

        return response()->json([
            'ok' => true,
            'message' => __('site.order.success'),
        ]);
    }
}
