<?php

namespace App\Filament\Resources\OrderRequests\Tables;

use App\Filament\Support\Labels;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Когда')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                TextColumn::make('name')
                    ->label('Имя')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('tariff_zone')
                    ->label('Зона')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => Labels::ZONES[$state] ?? '—')
                    ->color('info'),
                TextColumn::make('pickup_address')
                    ->label('Откуда')
                    ->limit(30)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => Labels::STATUSES[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'new' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('locale')
                    ->label('Язык')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => strtoupper($state)),
                TextColumn::make('device_name')
                    ->label('Устройство')
                    ->limit(28)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('country_code')
                    ->label('Страна')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(Labels::STATUSES),
                SelectFilter::make('tariff_zone')
                    ->label('Зона')
                    ->options(Labels::ZONES),
                SelectFilter::make('locale')
                    ->label('Язык')
                    ->options(Labels::LOCALES),
            ])
            ->recordActions([
                Action::make('call')
                    ->label('Позвонить')
                    ->icon('heroicon-o-phone')
                    ->color('success')
                    ->url(fn ($record) => 'tel:'.preg_replace('/[^\d+]/', '', $record->phone))
                    ->openUrlInNewTab(),
                Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('info')
                    ->url(fn ($record) => 'https://wa.me/'.preg_replace('/\D/', '', $record->phone))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ]);
    }
}
