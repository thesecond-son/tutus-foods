<?php

namespace App\Filament\Resources\CorporateRequests;

use App\Filament\Resources\CorporateRequests\Pages\CreateCorporateRequest;
use App\Filament\Resources\CorporateRequests\Pages\EditCorporateRequest;
use App\Filament\Resources\CorporateRequests\Pages\ListCorporateRequests;
use App\Filament\Resources\CorporateRequests\Pages\ViewCorporateRequest;
use App\Filament\Resources\CorporateRequests\Schemas\CorporateRequestForm;
use App\Filament\Resources\CorporateRequests\Schemas\CorporateRequestInfolist;
use App\Filament\Resources\CorporateRequests\Tables\CorporateRequestsTable;
use App\Models\CorporateRequest;
use BackedEnum;
use UnitEnum;  
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CorporateRequestResource extends Resource
{
    protected static ?string $model = CorporateRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Order Management';

    protected static ?string $recordTitleAttribute = 'CorporateRequest';

    public static function form(Schema $schema): Schema
    {
        return CorporateRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CorporateRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CorporateRequestsTable::configure($table);
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
            'index' => ListCorporateRequests::route('/'),
            'create' => CreateCorporateRequest::route('/create'),
            'view' => ViewCorporateRequest::route('/{record}'),
            'edit' => EditCorporateRequest::route('/{record}/edit'),
        ];
    }

}
