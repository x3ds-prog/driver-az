<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\TrackSiteVisit;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'home'])->name('home');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::post('/order', [OrderController::class, 'store'])->middleware('throttle:5,1')->name('order.store');

// Старые адреса *.html открывают каноническую страницу языка.
Route::redirect('/en.html', '/en', 301);
Route::redirect('/tr.html', '/tr', 301);
Route::redirect('/ru.html', '/ru', 301);
Route::redirect('/az.html', '/az', 301);
Route::redirect('/index.html', '/az', 301);

Route::get('/{locale}', [LandingController::class, 'show'])
    ->whereIn('locale', array_keys(LandingController::LOCALES))
    ->middleware(TrackSiteVisit::class)
    ->name('landing');
