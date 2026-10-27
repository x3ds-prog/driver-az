<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $locales = array_keys(LandingController::LOCALES);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($locales as $locale) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.e(route('landing', ['locale' => $locale]))."</loc>\n";
            $xml .= '    <lastmod>'.now()->toDateString()."</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= '    <priority>'.($locale === 'az' ? '1.00' : '0.80')."</priority>\n";

            foreach ($locales as $alternate) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="'.LandingController::LOCALES[$alternate]['hreflang'].'" href="'.e(route('landing', ['locale' => $alternate]))."\"/>\n";
            }
            $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.e(route('landing', ['locale' => 'az']))."\"/>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
