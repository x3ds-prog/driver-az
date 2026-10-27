<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingController extends Controller
{
    public const LOCALES = [
        'az' => ['hreflang' => 'az', 'og' => 'az_AZ', 'label' => 'AZ'],
        'ru' => ['hreflang' => 'ru', 'og' => 'ru_RU', 'label' => 'RU'],
        'tr' => ['hreflang' => 'tr', 'og' => 'tr_TR', 'label' => 'TR'],
        'en' => ['hreflang' => 'en', 'og' => 'en_US', 'label' => 'EN'],
    ];

    public function home(): RedirectResponse
    {
        $preferred = substr((string) request()->getPreferredLanguage(array_keys(self::LOCALES)), 0, 2);

        return redirect()->route('landing', ['locale' => array_key_exists($preferred, self::LOCALES) ? $preferred : 'az']);
    }

    public function show(string $locale): View
    {
        abort_unless(array_key_exists($locale, self::LOCALES), 404);

        app()->setLocale($locale);

        return view('landing', [
            'locale' => $locale,
            'locales' => self::LOCALES,
        ]);
    }
}
