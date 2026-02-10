<?php

namespace App\Filament\Resources\PrivacyPolicySettings;

use App\Filament\Resources\PrivacyPolicySettings\Pages\CreatePrivacyPolicySetting;
use App\Filament\Resources\PrivacyPolicySettings\Pages\EditPrivacyPolicySetting;
use App\Filament\Resources\PrivacyPolicySettings\Pages\ListPrivacyPolicySettings;
use App\Filament\Resources\PrivacyPolicySettings\Pages\ViewPrivacyPolicySetting;
use App\Filament\Resources\PrivacyPolicySettings\Schemas\PrivacyPolicySettingForm;
use App\Filament\Resources\PrivacyPolicySettings\Schemas\PrivacyPolicySettingInfolist;
use App\Filament\Resources\PrivacyPolicySettings\Tables\PrivacyPolicySettingsTable;
use App\Models\PrivacyPolicySetting;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrivacyPolicySettingResource extends Resource
{
    protected static ?string $model = PrivacyPolicySetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Pages Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PrivacyPolicySettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PrivacyPolicySettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrivacyPolicySettingsTable::configure($table);
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
            'index' => ListPrivacyPolicySettings::route('/'),
            'create' => CreatePrivacyPolicySetting::route('/create'),
            'view' => ViewPrivacyPolicySetting::route('/{record}'),
            'edit' => EditPrivacyPolicySetting::route('/{record}/edit'),
        ];
    }
}
