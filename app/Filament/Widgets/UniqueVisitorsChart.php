<?php

namespace App\Filament\Widgets;

use App\Models\SiteVisit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class UniqueVisitorsChart extends ChartWidget
{
    protected ?string $heading = 'Уникальные посетители';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '320px';

    public ?string $filter = '14';

    protected function getFilters(): ?array
    {
        return [
            '7' => '7 дней',
            '14' => '14 дней',
            '30' => '30 дней',
            '90' => '90 дней',
        ];
    }

    protected function getData(): array
    {
        $days = (int) ($this->filter ?: 14);
        $from = now()->subDays($days - 1)->startOfDay();

        $rows = SiteVisit::humans()
            ->where('created_at', '>=', $from)
            ->get(['visitor_hash', 'created_at']);

        $labels = [];
        $unique = [];
        $views = [];

        for ($i = 0; $i < $days; $i++) {
            $day = Carbon::parse($from)->addDays($i);
            $labels[] = $day->format('d.m');

            $dayRows = $rows->filter(fn ($row) => $row->created_at->isSameDay($day));
            $unique[] = $dayRows->unique('visitor_hash')->count();
            $views[] = $dayRows->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Уникальные посетители',
                    'data' => $unique,
                    'borderColor' => '#2563EB',
                    'backgroundColor' => 'rgba(37, 99, 235, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Всего просмотров',
                    'data' => $views,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.08)',
                    'fill' => false,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
