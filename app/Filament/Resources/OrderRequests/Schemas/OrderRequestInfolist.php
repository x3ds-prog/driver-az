<?php

namespace App\Filament\Resources\OrderRequests\Schemas;

use App\Filament\Support\Labels;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('phone')->label('Телефон')->copyable(),
                TextEntry::make('name')->label('Имя')->placeholder('—'),
                TextEntry::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Labels::STATUSES[$state] ?? $state),
                TextEntry::make('tariff_zone')
                    ->label('Зона')
                    ->formatStateUsing(fn (?string $state) => Labels::ZONES[$state] ?? '—'),
                TextEntry::make('pickup_address')->label('Откуда')->placeholder('—'),
                TextEntry::make('destination_address')->label('Куда')->placeholder('—'),
                TextEntry::make('car_type')->label('Автомобиль')->placeholder('—'),
                TextEntry::make('comment')->label('Комментарий')->placeholder('—')->columnSpanFull(),
                TextEntry::make('locale')->label('Язык')->formatStateUsing(fn (string $state) => strtoupper($state)),
                TextEntry::make('device_name')->label('Устройство')->placeholder('—'),
                TextEntry::make('ip_address')->label('IP (Cloudflare)')->placeholder('—'),
                TextEntry::make('country_code')->label('Страна')->placeholder('—'),
                TextEntry::make('created_at')->label('Создана')->dateTime('d.m.Y H:i'),
            ]);
    }
}
