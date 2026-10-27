<?php

namespace App\Filament\Resources\OrderRequests\Schemas;

use App\Filament\Support\Labels;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('phone')->label('Телефон')->required()->tel(),
                TextInput::make('name')->label('Имя'),
                Select::make('tariff_zone')->label('Тарифная зона')->options(Labels::ZONES),
                TextInput::make('pickup_address')->label('Откуда'),
                TextInput::make('destination_address')->label('Куда'),
                TextInput::make('car_type')->label('Автомобиль'),
                Textarea::make('comment')->label('Комментарий')->columnSpanFull(),
                Select::make('status')->label('Статус')->options(Labels::STATUSES)->required(),
                Select::make('locale')->label('Язык страницы')->options(Labels::LOCALES)->required(),
                TextInput::make('device_name')->label('Устройство клиента')->disabled(),
                TextInput::make('ip_address')->label('IP (Cloudflare)')->disabled(),
                TextInput::make('country_code')->label('Страна')->disabled(),
            ]);
    }
}
