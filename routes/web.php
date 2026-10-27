<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\TrackSiteVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'home'])->name('home');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::post('/order', [OrderController::class, 'store'])->middleware('throttle:5,1')->name('order.store');

// Старые адреса *.html открывают каноническую страницу языка.
// Query string сохраняется: без gclid и utm Google Реклама не засчитает переход.
foreach (['en.html' => 'en', 'tr.html' => 'tr', 'ru.html' => 'ru', 'az.html' => 'az', 'index.html' => 'az'] as $legacy => $locale) {
    Route::get('/'.$legacy, function (Request $request) use ($locale) {
        $target = route('landing', ['locale' => $locale]);
        $query = $request->getQueryString();

        return redirect()->to($query ? $target.'?'.$query : $target, 301);
    });
}

Route::get('/{locale}', [LandingController::class, 'show'])
    ->whereIn('locale', array_keys(LandingController::LOCALES))
    ->middleware(TrackSiteVisit::class)
    ->name('landing');
