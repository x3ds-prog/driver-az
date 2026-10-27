<?php

namespace App\Filament\Resources\SiteVisits;

use App\Filament\Resources\SiteVisits\Pages\ListSiteVisits;
use App\Filament\Resources\SiteVisits\Pages\ViewSiteVisit;
use App\Filament\Resources\SiteVisits\Schemas\SiteVisitInfolist;
use App\Filament\Resources\SiteVisits\Tables\SiteVisitsTable;
use App\Models\SiteVisit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SiteVisitResource extends Resource
{
    protected static ?string $model = SiteVisit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Посетители';

    protected static ?string $modelLabel = 'посещение';

    protected static ?string $pluralModelLabel = 'Посетители';

    protected static ?int $navigationSort = 2;

    public static function infolist(Schema $schema): Schema
    {
        return SiteVisitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteVisitsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteVisits::route('/'),
            'view' => ViewSiteVisit::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
