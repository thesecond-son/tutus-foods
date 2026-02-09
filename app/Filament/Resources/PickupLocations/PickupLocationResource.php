<?php

namespace App\Filament\Resources\PickupLocations;

use App\Filament\Resources\PickupLocations\Pages\CreatePickupLocation;
use App\Filament\Resources\PickupLocations\Pages\EditPickupLocation;
use App\Filament\Resources\PickupLocations\Pages\ListPickupLocations;
use App\Filament\Resources\PickupLocations\Pages\ViewPickupLocation;
use App\Filament\Resources\PickupLocations\Schemas\PickupLocationForm;
use App\Filament\Resources\PickupLocations\Schemas\PickupLocationInfolist;
use App\Filament\Resources\PickupLocations\Tables\PickupLocationsTable;
use App\Models\PickupLocation;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PickupLocationResource extends Resource
{
    protected static ?string $model = PickupLocation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Delivery Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PickupLocationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PickupLocationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PickupLocationsTable::configure($table);
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
            'index' => ListPickupLocations::route('/'),
            'create' => CreatePickupLocation::route('/create'),
            'view' => ViewPickupLocation::route('/{record}'),
            'edit' => EditPickupLocation::route('/{record}/edit'),
        ];
    }

    //Access Management
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }

}
