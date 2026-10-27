@php
    app()->setLocale(in_array(app()->getLocale(), ['az', 'ru', 'tr', 'en'], true) ? app()->getLocale() : 'az');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — NightDriver</title>
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#050814">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-[#050814] px-6 text-center text-slate-200" style="background-image: radial-gradient(700px 400px at 50% 0%, rgba(37,99,235,0.35), transparent 60%);">
    <p class="text-sm font-semibold tracking-widest text-blue-400">NIGHTDRIVER</p>
    <h1 class="mt-3 text-7xl font-black text-white">404</h1>
    <p class="mt-2 text-xl font-bold text-white">{{ __('site.not_found.title') }}</p>
    <p class="mt-2 max-w-sm text-sm text-slate-400">{{ __('site.not_found.text') }}</p>
    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
        <a href="{{ route('home') }}" class="rounded-2xl bg-blue-600 px-6 py-3.5 font-bold text-white transition hover:bg-blue-500">{{ __('site.not_found.home') }}</a>
        <a href="tel:+994514777172" class="rounded-2xl bg-white/10 px-6 py-3.5 font-bold text-white ring-1 ring-white/15 transition hover:bg-white/15">051 477 71 72</a>
    </div>
</body>
</html>
