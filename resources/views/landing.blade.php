@php
    $phone = '+994514777172';
    $phonePretty = '051 477 71 72';
    $waText = rawurlencode(__('site.wa_text'));
    $waLink = 'https://wa.me/994514777172?text='.$waText;
    $canonical = route('landing', ['locale' => $locale]);
    $ogImage = url('/assets/og-nightdriver.jpg');
    $zones = __('site.prices.zones');
    $faqs = __('site.faq.items');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ __('site.meta.title') }}</title>
    <meta name="description" content="{{ __('site.meta.description') }}">
    <meta name="keywords" content="{{ __('site.meta.keywords') }}">
    <meta name="theme-color" content="#050814">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="canonical" href="{{ $canonical }}">
    @foreach ($locales as $code => $meta)
        <link rel="alternate" hreflang="{{ $meta['hreflang'] }}" href="{{ route('landing', ['locale' => $code]) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ route('landing', ['locale' => 'az']) }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="NightDriver">
    <meta property="og:locale" content="{{ $locales[$locale]['og'] }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:title" content="{{ __('site.meta.title') }}">
    <meta property="og:description" content="{{ __('site.meta.description') }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ __('site.meta.og_alt') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('site.meta.title') }}">
    <meta name="twitter:description" content="{{ __('site.meta.description') }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'NightDriver',
            'description' => __('site.meta.description'),
            'telephone' => '+994514777172',
            'url' => $canonical,
            'image' => $ogImage,
            'priceRange' => '10-20 AZN',
            'currenciesAccepted' => 'AZN',
            'paymentAccepted' => 'Cash, Credit Card',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'Ziya Bunyadov prospekti, 1965',
                'addressLocality' => 'Baku',
                'addressRegion' => 'Narimanov',
                'addressCountry' => 'AZ',
            ],
            'areaServed' => [
                ['@type' => 'City', 'name' => 'Baku'],
                ['@type' => 'City', 'name' => 'Sumqayit'],
            ],
            'openingHours' => 'Mo-Su 00:00-23:59',
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($item) => [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
            ])->values(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        night: { 950: '#050814', 900: '#0B132B', 800: '#12203F' },
                        neon: '#3B82F6',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .glass { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
        .night-bg { background:
            radial-gradient(900px 500px at 80% -10%, rgba(37,99,235,0.35), transparent 60%),
            radial-gradient(700px 400px at 0% 20%, rgba(14,165,233,0.18), transparent 55%),
            linear-gradient(180deg, #050814 0%, #0B132B 100%); }
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.visible { opacity: 1; transform: none; }
        .zone-card.active { border-color: rgba(59,130,246,0.8); box-shadow: 0 0 0 1px rgba(59,130,246,0.6), 0 10px 40px rgba(37,99,235,0.25); }
        .bar-safe { padding-bottom: calc(0.75rem + env(safe-area-inset-bottom)); }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }
        .pulse-dot { animation: pulse-dot 1.6s ease-in-out infinite; }
    </style>
</head>
<body class="night-bg text-slate-200 antialiased pb-28">

<header class="sticky top-0 z-40 border-b border-white/10 bg-night-950/70 backdrop-blur-xl">
    <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
        <a href="{{ $canonical }}" class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600/20 ring-1 ring-blue-400/40">
                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13l2-5a2 2 0 011.9-1.4h10.2A2 2 0 0119 8l2 5M5 13h14v4a1 1 0 01-1 1h-1a1 1 0 01-1-1v-1H8v1a1 1 0 01-1 1H6a1 1 0 01-1-1v-4z"/><circle cx="7.5" cy="16.5" r="1" fill="currentColor"/><circle cx="16.5" cy="16.5" r="1" fill="currentColor"/></svg>
            </span>
            <span class="leading-tight">
                <span class="block text-base font-extrabold tracking-tight text-white">Night<span class="text-blue-400">Driver</span></span>
                <span class="block text-[11px] text-slate-400">{{ __('site.brand_tagline') }}</span>
            </span>
        </a>
        <nav class="hidden items-center gap-6 text-sm text-slate-300 md:flex">
            <a href="#prices" class="transition hover:text-white">{{ __('site.nav.prices') }}</a>
            <a href="#how" class="transition hover:text-white">{{ __('site.nav.how') }}</a>
            <a href="#faq" class="transition hover:text-white">{{ __('site.nav.faq') }}</a>
        </nav>
        <div class="flex items-center gap-3">
            <span class="hidden items-center gap-1.5 text-xs text-emerald-400 sm:flex">
                <span class="pulse-dot h-2 w-2 rounded-full bg-emerald-400"></span>{{ __('site.status_online') }}
            </span>
            <div class="flex rounded-full bg-white/5 p-0.5 ring-1 ring-white/10">
                @foreach ($locales as $code => $meta)
                    <a href="{{ route('landing', ['locale' => $code]) }}"
                       class="rounded-full px-2.5 py-1 text-xs font-semibold transition {{ $code === $locale ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white' }}">{{ $meta['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
</header>

<main>
    <section class="relative mx-auto max-w-6xl px-4 pb-10 pt-8 md:pt-16">
        <div class="grid items-center gap-8 md:grid-cols-2">
            <div>
                <p class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/5 px-3 py-1 text-xs font-medium text-blue-300 ring-1 ring-white/10">
                    <span class="pulse-dot h-1.5 w-1.5 rounded-full bg-blue-400"></span>{{ __('site.hero.eyebrow') }}
                </p>
                <h1 class="text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl">{{ __('site.hero.title') }}</h1>
                <p class="mt-4 max-w-xl text-base text-slate-300 sm:text-lg">{{ __('site.hero.subtitle') }}</p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ $waLink }}" data-wa target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-2 rounded-2xl bg-emerald-500 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-500/30 transition hover:scale-[1.02] hover:bg-emerald-400 active:scale-95">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 004.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.76 14.16c-.24.68-1.4 1.3-1.94 1.38-.5.07-1.13.1-1.82-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.16-1.55-1.16-2.95s.73-2.09 1-2.38c.26-.29.57-.36.76-.36h.55c.17 0 .41-.07.64.49.24.58.82 2 .89 2.15.07.14.12.31.02.51-.09.19-.14.31-.28.48-.14.17-.29.37-.42.5-.14.14-.28.29-.12.56.16.27.71 1.17 1.52 1.9 1.05.93 1.93 1.22 2.2 1.36.28.14.44.12.6-.07.16-.19.7-.81.88-1.09.19-.28.37-.23.62-.14.26.09 1.63.77 1.91.91.28.14.46.21.53.33.07.12.07.69-.17 1.36z"/></svg>
                        {{ __('site.hero.whatsapp') }}
                    </a>
                    <a href="tel:{{ $phone }}"
                       class="flex items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-blue-600/30 transition hover:scale-[1.02] hover:bg-blue-500 active:scale-95">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11.4 11.4 0 003.6.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.4 11.4 0 00.57 3.6 1 1 0 01-.25 1l-2.22 2.2z"/></svg>
                        {{ __('site.hero.call') }}
                    </a>
                </div>
                <p class="mt-3 text-sm text-slate-400">{{ __('site.hero.note') }} · {{ $phonePretty }}</p>
            </div>
            <div class="relative">
                <div class="absolute inset-x-8 -bottom-2 h-16 rounded-full bg-blue-500/30 blur-2xl"></div>
                <img src="/assets/og-nightdriver.jpg" alt="{{ __('site.meta.og_alt') }}"
                     class="relative w-full rounded-3xl object-cover shadow-2xl ring-1 ring-white/10" width="1200" height="630">
            </div>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4">
            @foreach (__('site.stats') as $stat)
                <div class="reveal glass rounded-2xl p-4 text-center">
                    <p class="text-lg font-extrabold text-white sm:text-2xl">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-xs text-slate-400 sm:text-sm">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="prices" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-12">
        <h2 class="reveal text-2xl font-extrabold text-white sm:text-3xl">{{ __('site.prices.title') }}</h2>
        <p class="reveal mt-2 max-w-2xl text-slate-400">{{ __('site.prices.subtitle') }}</p>
        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach ($zones as $key => $zone)
                <button type="button" data-zone="{{ $key }}"
                        class="zone-card reveal glass rounded-2xl p-5 text-left transition hover:border-blue-400/50 {{ $loop->first ? 'active' : '' }}">
                    <span class="block text-sm text-slate-400">{{ $zone['name'] }}</span>
                    <span class="mt-1 block text-2xl font-extrabold text-white">
                        @if (__('site.prices.from'))<span class="text-sm font-medium text-slate-400">{{ __('site.prices.from') }}</span> @endif{{ $zone['price'] }}
                    </span>
                    <span class="mt-1 block text-sm text-slate-400">{{ $zone['hint'] }}</span>
                    <span class="mt-3 inline-block text-sm font-semibold text-blue-400">{{ __('site.prices.order') }} →</span>
                </button>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-6">
        <div class="reveal glass grid items-center gap-6 rounded-3xl p-6 sm:grid-cols-[1fr_auto_1fr] sm:p-8">
            <div class="text-center">
                <p class="text-sm text-slate-400">{{ __('site.compare.fine_label') }}</p>
                <p class="mt-1 text-4xl font-black text-red-400 line-through decoration-2">{{ __('site.compare.fine_value') }}</p>
            </div>
            <div class="hidden h-16 w-px bg-white/10 sm:block"></div>
            <div class="text-center">
                <p class="text-sm text-slate-400">{{ __('site.compare.our_label') }}</p>
                <p class="mt-1 text-4xl font-black text-emerald-400">{{ __('site.compare.our_value') }}</p>
            </div>
            <p class="text-sm leading-relaxed text-slate-300 sm:col-span-3">{{ __('site.compare.text') }}</p>
        </div>
    </section>

    <section id="how" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-12">
        <h2 class="reveal text-2xl font-extrabold text-white sm:text-3xl">{{ __('site.how.title') }}</h2>
        <div class="mt-6 grid gap-3 md:grid-cols-3">
            @foreach (__('site.how.steps') as $step)
                <div class="reveal glass rounded-2xl p-5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">{{ $loop->iteration }}</span>
                    <h3 class="mt-3 text-lg font-bold text-white">{{ $step['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-400">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-6">
        <h2 class="reveal text-2xl font-extrabold text-white sm:text-3xl">{{ __('site.benefits.title') }}</h2>
        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach (__('site.benefits.items') as $benefit)
                <div class="reveal glass rounded-2xl p-5">
                    <h3 class="text-base font-bold text-white">{{ $benefit['title'] }}</h3>
                    <p class="mt-1 text-sm leading-relaxed text-slate-400">{{ $benefit['text'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section id="faq" class="mx-auto max-w-6xl scroll-mt-20 px-4 py-12">
        <h2 class="reveal text-2xl font-extrabold text-white sm:text-3xl">{{ __('site.faq.title') }}</h2>
        <div class="mt-6 space-y-3">
            @foreach ($faqs as $item)
                <details class="reveal glass group rounded-2xl">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 text-sm font-semibold text-white sm:text-base">
                        {{ $item['q'] }}
                        <svg class="h-5 w-5 shrink-0 text-slate-400 transition group-open:rotate-180" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                    </summary>
                    <p class="px-5 pb-5 text-sm leading-relaxed text-slate-400">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
</main>

<footer class="border-t border-white/10 bg-night-950/80">
    <div class="mx-auto max-w-6xl px-4 py-10 text-center">
        <p class="text-lg font-extrabold text-white">Night<span class="text-blue-400">Driver</span></p>
        <a href="tel:{{ $phone }}" class="mt-2 block text-xl font-bold tracking-wide text-blue-400">{{ $phonePretty }}</a>
        <p class="mt-3 text-sm text-slate-400">{{ __('site.footer.address') }}</p>
        <p class="mt-6 text-xs text-slate-500">&copy; {{ date('Y') }} NightDriver. {{ __('site.footer.rights') }}</p>
    </div>
</footer>

<div class="fixed inset-x-0 bottom-0 z-40 border-t border-white/10 bg-night-950/85 backdrop-blur-xl bar-safe">
    <div class="mx-auto flex max-w-6xl gap-3 px-4 pt-3">
        <a href="tel:{{ $phone }}"
           class="flex w-1/2 items-center justify-center gap-2 rounded-2xl bg-blue-600 py-3.5 text-sm font-bold text-white transition active:scale-95 sm:text-base">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2a1 1 0 011-.25 11.4 11.4 0 003.6.57 1 1 0 011 1V20a1 1 0 01-1 1A17 17 0 013 4a1 1 0 011-1h3.5a1 1 0 011 1 11.4 11.4 0 00.57 3.6 1 1 0 01-.25 1l-2.22 2.2z"/></svg>
            {{ __('site.bar.call') }}
        </a>
        <a href="{{ $waLink }}" data-wa target="_blank" rel="noopener noreferrer"
           class="flex w-1/2 items-center justify-center gap-2 rounded-2xl bg-emerald-500 py-3.5 text-sm font-bold text-white transition active:scale-95 sm:text-base">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38a9.9 9.9 0 004.74 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm5.76 14.16c-.24.68-1.4 1.3-1.94 1.38-.5.07-1.13.1-1.82-.11-.42-.13-.95-.31-1.64-.6-2.88-1.24-4.76-4.14-4.9-4.33-.14-.19-1.16-1.55-1.16-2.95s.73-2.09 1-2.38c.26-.29.57-.36.76-.36h.55c.17 0 .41-.07.64.49.24.58.82 2 .89 2.15.07.14.12.31.02.51-.09.19-.14.31-.28.48-.14.17-.29.37-.42.5-.14.14-.28.29-.12.56.16.27.71 1.17 1.52 1.9 1.05.93 1.93 1.22 2.2 1.36.28.14.44.12.6-.07.16-.19.7-.81.88-1.09.19-.28.37-.23.62-.14.26.09 1.63.77 1.91.91.28.14.46.21.53.33.07.12.07.69-.17 1.36z"/></svg>
            {{ __('site.bar.whatsapp') }}
        </a>
    </div>
    <button type="button" id="open-order" class="mx-auto mt-1 block px-4 pb-1 text-xs text-slate-400 underline-offset-2 hover:text-white hover:underline">
        {{ __('site.order.fallback_link') }}
    </button>
</div>

<div id="order-sheet" class="fixed inset-0 z-50 hidden">
    <div id="order-backdrop" class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    <div class="absolute inset-x-0 bottom-0 mx-auto max-w-lg rounded-t-3xl border border-white/10 bg-night-900 p-6 shadow-2xl bar-safe">
        <div class="mx-auto mb-4 h-1.5 w-12 rounded-full bg-white/20"></div>
        <h3 class="text-lg font-extrabold text-white">{{ __('site.order.title') }}</h3>
        <p class="mt-1 text-sm text-slate-400">{{ __('site.order.subtitle') }}</p>
        <form id="order-form" class="mt-4 space-y-3" novalidate>
            <input type="text" name="name" maxlength="120" placeholder="{{ __('site.order.name') }}" autocomplete="name"
                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-blue-500">
            <input type="tel" name="phone" required maxlength="30" placeholder="{{ __('site.order.phone') }}" autocomplete="tel"
                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-blue-500">
            <input type="text" name="pickup_address" maxlength="255" placeholder="{{ __('site.order.pickup') }}"
                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-blue-500">
            <input type="text" name="destination_address" maxlength="255" placeholder="{{ __('site.order.destination') }}"
                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-blue-500">
            <input type="hidden" name="tariff_zone" id="tariff-zone" value="baku_city">
            <textarea name="comment" maxlength="1000" rows="2" placeholder="{{ __('site.order.comment') }}"
                      class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none placeholder:text-slate-500 focus:border-blue-500"></textarea>
            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-action="order_driver" data-theme="dark"></div>
            <button type="submit" id="order-submit"
                    class="w-full rounded-2xl bg-blue-600 py-3.5 text-base font-bold text-white transition hover:bg-blue-500 active:scale-95">{{ __('site.order.submit') }}</button>
            <p id="order-result" class="hidden text-center text-sm"></p>
        </form>
    </div>
</div>

<script>
    const zones = @json($zones);
    const waBase = @json('https://wa.me/994514777172?text=');
    const waIntro = @json(__('site.wa_text'));
    const locale = @json($locale);
    const messages = @json(__('site.order'));

    let selectedZone = 'baku_city';

    document.querySelectorAll('a[data-wa]').forEach((link) => {
        link.addEventListener('click', () => {
            const zone = zones[selectedZone];
            link.href = waBase + encodeURIComponent(waIntro + zone.name + ' (' + zone.price + ')');
        });
    });

    document.querySelectorAll('[data-zone]').forEach((card) => {
        card.addEventListener('click', () => {
            selectedZone = card.dataset.zone;
            document.getElementById('tariff-zone').value = selectedZone;
            document.querySelectorAll('[data-zone]').forEach((c) => c.classList.toggle('active', c === card));
            const zone = zones[selectedZone];
            window.open(waBase + encodeURIComponent(waIntro + zone.name + ' (' + zone.price + ')'), '_blank', 'noopener');
        });
    });

    const sheet = document.getElementById('order-sheet');
    const openSheet = () => sheet.classList.remove('hidden');
    const closeSheet = () => sheet.classList.add('hidden');
    document.getElementById('open-order').addEventListener('click', openSheet);
    document.getElementById('order-backdrop').addEventListener('click', closeSheet);

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const target = document.querySelector(link.getAttribute('href'));
            if (!target) return;
            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    document.querySelectorAll('.reveal').forEach((el) => observer.observe(el));

    const form = document.getElementById('order-form');
    const result = document.getElementById('order-result');
    const submitButton = document.getElementById('order-submit');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        result.classList.add('hidden');
        submitButton.disabled = true;
        submitButton.textContent = messages.sending;

        const payload = {
            name: form.name.value,
            phone: form.phone.value,
            pickup_address: form.pickup_address.value,
            destination_address: form.destination_address.value,
            tariff_zone: form.tariff_zone.value,
            comment: form.comment.value,
            locale: locale,
            'cf-turnstile-response': form.querySelector('[name="cf-turnstile-response"]')?.value ?? '',
        };

        const hints = navigator.userAgentData;
        if (hints) {
            payload.device_platform = hints.platform || '';
            payload.device_mobile = !!hints.mobile;
            try {
                const high = await hints.getHighEntropyValues(['model']);
                payload.device_model = high.model || '';
            } catch (e) { /* модель останется неизвестной */ }
        }

        try {
            const response = await fetch(@json(route('order.store')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @json(csrf_token()),
                },
                body: JSON.stringify(payload),
            });
            const data = await response.json().catch(() => ({}));
            result.textContent = response.ok ? messages.success : (data.message || messages.error);
            result.className = 'text-center text-sm ' + (response.ok ? 'text-emerald-400' : 'text-red-400');
            if (response.ok) form.reset();
        } catch (e) {
            result.textContent = messages.error;
            result.className = 'text-center text-sm text-red-400';
        }

        if (window.turnstile) window.turnstile.reset();
        submitButton.disabled = false;
        submitButton.textContent = messages.submit;
    });
</script>
</body>
</html>
