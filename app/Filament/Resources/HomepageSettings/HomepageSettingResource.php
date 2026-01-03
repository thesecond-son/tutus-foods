<?php

namespace App\Filament\Resources\HomepageSettings;

use App\Filament\Resources\HomepageSettings\Pages\CreateHomepageSetting;
use App\Filament\Resources\HomepageSettings\Pages\EditHomepageSetting;
use App\Filament\Resources\HomepageSettings\Pages\ListHomepageSettings;
use App\Filament\Resources\HomepageSettings\Pages\ViewHomepageSetting;
use App\Filament\Resources\HomepageSettings\Schemas\HomepageSettingForm;
use App\Filament\Resources\HomepageSettings\Schemas\HomepageSettingInfolist;
use App\Filament\Resources\HomepageSettings\Tables\HomepageSettingsTable;
use App\Models\HomepageSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HomepageSettingResource extends Resource
{
    protected static ?string $model = HomepageSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HomepageSettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HomepageSettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomepageSettingsTable::configure($table);
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
            'index' => ListHomepageSettings::route('/'),
            'create' => CreateHomepageSetting::route('/create'),
            'view' => ViewHomepageSetting::route('/{record}'),
            'edit' => EditHomepageSetting::route('/{record}/edit'),
        ];
    }

    //Access Management
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
