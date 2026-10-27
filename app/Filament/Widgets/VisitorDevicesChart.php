<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Filament\Widgets\ChartWidget;

class VisitorDevicesChart extends ChartWidget
{
    protected ?string $heading = 'Телефоны и компьютеры за 30 дней';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $rows = SiteVisit::humans()
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('device_brand_model, count(distinct visitor_hash) as visitors')
            ->groupBy('device_brand_model')
            ->orderByDesc('visitors')
            ->limit(8)
            ->pluck('visitors', 'device_brand_model');

        $palette = ['#2563EB', '#10B981', '#F59E0B', '#8B5CF6', '#EF4444', '#06B6D4', '#EC4899', '#64748B'];

        return [
            'datasets' => [
                [
                    'label' => 'Уникальные посетители',
                    'data' => $rows->values()->all(),
                    'backgroundColor' => array_slice($palette, 0, $rows->count()),
                ],
            ],
            'labels' => $rows->keys()->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
