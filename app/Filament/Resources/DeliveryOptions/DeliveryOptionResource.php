<?php

namespace App\Filament\Resources\DeliveryOptions;

use App\Filament\Resources\DeliveryOptions\Pages\CreateDeliveryOption;
use App\Filament\Resources\DeliveryOptions\Pages\EditDeliveryOption;
use App\Filament\Resources\DeliveryOptions\Pages\ListDeliveryOptions;
use App\Filament\Resources\DeliveryOptions\Pages\ViewDeliveryOption;
use App\Filament\Resources\DeliveryOptions\Schemas\DeliveryOptionForm;
use App\Filament\Resources\DeliveryOptions\Schemas\DeliveryOptionInfolist;
use App\Filament\Resources\DeliveryOptions\Tables\DeliveryOptionsTable;
use App\Models\DeliveryOption;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryOptionResource extends Resource
{
    protected static ?string $model = DeliveryOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Delivery Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DeliveryOptionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DeliveryOptionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryOptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDeliveryOptions::route('/'),
            'create' => CreateDeliveryOption::route('/create'),
            'view' => ViewDeliveryOption::route('/{record}'),
            'edit' => EditDeliveryOption::route('/{record}/edit'),
        ];
    }

    //Access Management
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
