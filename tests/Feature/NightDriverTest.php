<?php

namespace Tests\Feature;

use App\Mail\OrderReceived;
use App\Models\OrderRequest;
use App\Models\SiteVisit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NightDriverTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_locale_renders_with_seo_tags(): void
    {
        foreach (['az', 'ru', 'tr', 'en'] as $locale) {
            $response = $this->get('/'.$locale);

            $response->assertOk();
            $response->assertSee('rel="canonical" href="'.url('/'.$locale).'"', false);
            $response->assertSee('hreflang="az"', false);
            $response->assertSee('hreflang="ru"', false);
            $response->assertSee('hreflang="tr"', false);
            $response->assertSee('hreflang="en"', false);
            $response->assertSee('hreflang="x-default"', false);
            $response->assertSee('og:image" content="'.url('/assets/og-nightdriver.jpg').'"', false);
            $response->assertSee('FAQPage', false);
            $response->assertSee('cf-turnstile', false);
            $response->assertSee('10 AZN', false);
            $response->assertDontSee('Alodrive', false);
            $response->assertDontSee('Felix', false);
        }
    }

    public function test_home_redirects_to_a_locale(): void
    {
        $this->get('/')->assertRedirect();
    }

    public function test_sitemap_lists_all_locales(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('/az', false)
            ->assertSee('/ru', false)
            ->assertSee('/tr', false)
            ->assertSee('/en', false)
            ->assertSee('hreflang="x-default"', false);
    }

    public function test_visit_is_tracked_with_cloudflare_headers(): void
    {
        $this->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1',
            'CF-Connecting-IP' => '5.44.123.10',
            'CF-IPCountry' => 'AZ',
        ])->get('/ru')->assertOk();

        $visit = SiteVisit::first();
        $this->assertNotNull($visit);
        $this->assertSame('5.44.123.10', $visit->ip_address);
        $this->assertSame('AZ', $visit->country_code);
        $this->assertSame('mobile', $visit->device_type);
        $this->assertSame('Apple iPhone', $visit->device_brand_model);
        $this->assertSame('iOS', $visit->os);
        $this->assertSame('Safari', $visit->browser);
        $this->assertSame('ru', $visit->locale);
    }

    public function test_bots_are_not_tracked(): void
    {
        $this->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1)'])
            ->get('/az')->assertOk();

        $this->assertSame(0, SiteVisit::count());
    }

    public function test_order_requires_valid_turnstile_token(): void
    {
        Http::fake([
            'challenges.cloudflare.com/*' => Http::response([
                'success' => true,
                'action' => 'order_driver',
                'hostname' => 'localhost',
            ]),
        ]);
        Mail::fake();

        $this->postJson('/order', [
            'phone' => '+994501112233',
            'pickup_address' => 'Nizami 12',
            'tariff_zone' => 'baku_city',
            'locale' => 'az',
            'cf-turnstile-response' => 'token-ok',
            'device_model' => 'Pixel 8',
            'device_platform' => 'Android',
            'device_mobile' => true,
        ], ['CF-Connecting-IP' => '5.44.123.10', 'CF-IPCountry' => 'AZ'])
            ->assertOk()
            ->assertJson(['ok' => true]);

        $order = OrderRequest::first();
        $this->assertSame('+994501112233', $order->phone);
        $this->assertSame('baku_city', $order->tariff_zone);
        $this->assertSame('new', $order->status);
        $this->assertSame('5.44.123.10', $order->ip_address);
        $this->assertStringContainsString('Pixel 8', $order->device_name);

        Mail::assertSent(OrderReceived::class);
    }

    public function test_order_is_rejected_when_turnstile_fails(): void
    {
        Http::fake([
            'challenges.cloudflare.com/*' => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']]),
        ]);

        $this->postJson('/order', [
            'phone' => '+994501112233',
            'locale' => 'ru',
            'cf-turnstile-response' => 'bad-token',
        ])->assertStatus(422);

        $this->assertSame(0, OrderRequest::count());
    }

    public function test_order_validates_phone(): void
    {
        $this->postJson('/order', ['phone' => '12'])->assertStatus(422);
    }
}
