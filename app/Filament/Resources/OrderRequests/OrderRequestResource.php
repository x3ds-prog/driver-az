<?php

namespace App\Filament\Resources\OrderRequests;

use App\Filament\Resources\OrderRequests\Pages\EditOrderRequest;
use App\Filament\Resources\OrderRequests\Pages\ListOrderRequests;
use App\Filament\Resources\OrderRequests\Pages\ViewOrderRequest;
use App\Filament\Resources\OrderRequests\Schemas\OrderRequestForm;
use App\Filament\Resources\OrderRequests\Schemas\OrderRequestInfolist;
use App\Filament\Resources\OrderRequests\Tables\OrderRequestsTable;
use App\Models\OrderRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderRequestResource extends Resource
{
    protected static ?string $model = OrderRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoneArrowDownLeft;

    protected static ?string $navigationLabel = 'Заявки';

    protected static ?string $modelLabel = 'заявка';

    protected static ?string $pluralModelLabel = 'Заявки';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return OrderRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrderRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrderRequestsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        $count = OrderRequest::where('status', 'new')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrderRequests::route('/'),
            'view' => ViewOrderRequest::route('/{record}'),
            'edit' => EditOrderRequest::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['phone', 'name', 'pickup_address'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->phone;
    }
}
