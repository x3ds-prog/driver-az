<?php

namespace App\Filament\Support;

class Labels
{
    public const STATUSES = [
        'new' => 'Новая',
        'processing' => 'В работе',
        'completed' => 'Завершена',
        'cancelled' => 'Отменена',
    ];

    public const ZONES = [
        'baku_city' => 'Баку, внутри города',
        'settlements' => 'Поселки Баку',
        'sumqayit_villages' => 'Сумгаит ↔ села Баку',
        'sumqayit_city' => 'Внутри Сумгаита',
    ];

    public const LOCALES = [
        'az' => 'AZ',
        'ru' => 'RU',
        'tr' => 'TR',
        'en' => 'EN',
    ];

    public const DEVICE_TYPES = [
        'mobile' => 'Телефон',
        'tablet' => 'Планшет',
        'desktop' => 'Компьютер',
    ];
}
