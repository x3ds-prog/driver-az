<?php

namespace App\Filament\Resources\SiteVisits\Schemas;

use App\Filament\Support\Labels;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SiteVisitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')->label('Время')->dateTime('d.m.Y H:i'),
                TextEntry::make('device_type')
                    ->label('Тип')
                    ->formatStateUsing(fn (?string $state) => Labels::DEVICE_TYPES[$state] ?? '—'),
                TextEntry::make('device_brand_model')->label('Устройство'),
                TextEntry::make('os')->label('ОС'),
                TextEntry::make('browser')->label('Браузер'),
                TextEntry::make('ip_address')->label('IP (Cloudflare)')->copyable(),
                TextEntry::make('country_code')->label('Страна (CF-IPCountry)')->placeholder('—'),
                TextEntry::make('locale')->label('Язык страницы')->formatStateUsing(fn (?string $state) => strtoupper((string) $state)),
                TextEntry::make('path')->label('Страница'),
                TextEntry::make('referer')->label('Откуда пришел')->placeholder('прямой заход'),
            ]);
    }
}
