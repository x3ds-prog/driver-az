<?php

namespace App\Filament\Widgets;

use App\Models\OrderRequest;
use App\Models\SiteVisit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $uniqueToday = $this->uniqueBetween(now()->startOfDay(), now());
        $uniqueWeek = $this->uniqueBetween(now()->subDays(6)->startOfDay(), now());
        $uniqueMonth = $this->uniqueBetween(now()->subDays(29)->startOfDay(), now());

        $viewsWeek = SiteVisit::humans()->where('created_at', '>=', now()->subDays(6)->startOfDay())->count();
        $mobileWeek = SiteVisit::humans()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->where('device_type', 'mobile')
            ->count();
        $mobileShare = $viewsWeek > 0 ? round($mobileWeek / $viewsWeek * 100) : 0;

        $ordersToday = OrderRequest::where('created_at', '>=', now()->startOfDay())->count();
        $ordersNew = OrderRequest::where('status', 'new')->count();

        return [
            Stat::make('Уникальные сегодня', $uniqueToday)
                ->description($uniqueWeek.' за 7 дней · '.$uniqueMonth.' за 30 дней')
                ->descriptionIcon('heroicon-m-users')
                ->chart($this->uniqueSparkline())
                ->color('primary'),
            Stat::make('Просмотры за 7 дней', $viewsWeek)
                ->description($mobileShare.'% с телефонов')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('info'),
            Stat::make('Новые заявки', $ordersNew)
                ->description($ordersToday.' поступило сегодня')
                ->descriptionIcon('heroicon-m-phone-arrow-down-left')
                ->color($ordersNew > 0 ? 'warning' : 'success'),
        ];
    }

    private function uniqueBetween($from, $to): int
    {
        return SiteVisit::humans()
            ->whereBetween('created_at', [$from, $to])
            ->distinct('visitor_hash')
            ->count('visitor_hash');
    }

    /**
     * @return array<int, int>
     */
    private function uniqueSparkline(): array
    {
        return collect(range(6, 0))
            ->map(fn (int $daysAgo) => $this->uniqueBetween(
                now()->subDays($daysAgo)->startOfDay(),
                now()->subDays($daysAgo)->endOfDay()
            ))
            ->all();
    }
}
