<?php

namespace App\Filament\Resources\SiteVisits\Tables;

use App\Filament\Support\Labels;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SiteVisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Время')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('device_type')
                    ->label('Тип')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => Labels::DEVICE_TYPES[$state] ?? '—')
                    ->color(fn (?string $state) => match ($state) {
                        'mobile' => 'success',
                        'tablet' => 'info',
                        'desktop' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('device_brand_model')
                    ->label('Устройство')
                    ->searchable(),
                TextColumn::make('os')
                    ->label('ОС')
                    ->toggleable(),
                TextColumn::make('browser')
                    ->label('Браузер')
                    ->toggleable(),
                TextColumn::make('ip_address')
                    ->label('IP (Cloudflare)')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('country_code')
                    ->label('Страна')
                    ->placeholder('—'),
                TextColumn::make('locale')
                    ->label('Язык')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => strtoupper((string) $state)),
                TextColumn::make('path')
                    ->label('Страница')
                    ->toggleable(),
                IconColumn::make('is_bot')
                    ->label('Бот')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('device_type')
                    ->label('Тип устройства')
                    ->options(Labels::DEVICE_TYPES),
                SelectFilter::make('os')
                    ->label('ОС')
                    ->options(fn () => \App\Models\SiteVisit::query()->distinct()->pluck('os', 'os')->filter()->all()),
                SelectFilter::make('locale')
                    ->label('Язык')
                    ->options(Labels::LOCALES),
                SelectFilter::make('country_code')
                    ->label('Страна')
                    ->options(fn () => \App\Models\SiteVisit::query()->distinct()->pluck('country_code', 'country_code')->filter()->all()),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
