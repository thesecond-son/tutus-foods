<?php

namespace App\Filament\Resources\CheckoutPageSettings;

use App\Filament\Resources\CheckoutPageSettings\Pages\CreateCheckoutPageSetting;
use App\Filament\Resources\CheckoutPageSettings\Pages\EditCheckoutPageSetting;
use App\Filament\Resources\CheckoutPageSettings\Pages\ListCheckoutPageSettings;
use App\Filament\Resources\CheckoutPageSettings\Pages\ViewCheckoutPageSetting;
use App\Filament\Resources\CheckoutPageSettings\Schemas\CheckoutPageSettingForm;
use App\Filament\Resources\CheckoutPageSettings\Schemas\CheckoutPageSettingInfolist;
use App\Filament\Resources\CheckoutPageSettings\Tables\CheckoutPageSettingsTable;
use App\Models\CheckoutPageSetting;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CheckoutPageSettingResource extends Resource
{
    protected static ?string $model = CheckoutPageSetting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static string | UnitEnum | null $navigationGroup = 'Pages Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CheckoutPageSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CheckoutPageSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CheckoutPageSettingsTable::configure($table);
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
            'index' => ListCheckoutPageSettings::route('/'),
            'create' => CreateCheckoutPageSetting::route('/create'),
            'view' => ViewCheckoutPageSetting::route('/{record}'),
            'edit' => EditCheckoutPageSetting::route('/{record}/edit'),
        ];
    }
}
