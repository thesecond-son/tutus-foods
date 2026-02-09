<?php

namespace App\Filament\Resources\AboutUsSettings;

use App\Filament\Resources\AboutUsSettings\Pages\CreateAboutUsSettings;
use App\Filament\Resources\AboutUsSettings\Pages\EditAboutUsSettings;
use App\Filament\Resources\AboutUsSettings\Pages\ListAboutUsSettings;
use App\Filament\Resources\AboutUsSettings\Pages\ViewAboutUsSettings;
use App\Filament\Resources\AboutUsSettings\Schemas\AboutUsSettingsForm;
use App\Filament\Resources\AboutUsSettings\Schemas\AboutUsSettingsInfolist;
use App\Filament\Resources\AboutUsSettings\Tables\AboutUsSettingsTable;
use App\Models\AboutUsSettings;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AboutUsSettingsResource extends Resource
{
    protected static ?string $model = AboutUsSettings::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Pages Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return AboutUsSettingsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AboutUsSettingsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AboutUsSettingsTable::configure($table);
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
            'index' => ListAboutUsSettings::route('/'),
            'create' => CreateAboutUsSettings::route('/create'),
            'view' => ViewAboutUsSettings::route('/{record}'),
            'edit' => EditAboutUsSettings::route('/{record}/edit'),
        ];
    }
}
