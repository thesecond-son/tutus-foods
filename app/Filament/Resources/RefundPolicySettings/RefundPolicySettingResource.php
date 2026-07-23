<?php

namespace App\Filament\Resources\RefundPolicySettings;

use App\Filament\Resources\RefundPolicySettings\Pages\CreateRefundPolicySetting;
use App\Filament\Resources\RefundPolicySettings\Pages\EditRefundPolicySetting;
use App\Filament\Resources\RefundPolicySettings\Pages\ListRefundPolicySettings;
use App\Filament\Resources\RefundPolicySettings\Pages\ViewRefundPolicySetting;
use App\Filament\Resources\RefundPolicySettings\Schemas\RefundPolicySettingForm;
use App\Filament\Resources\RefundPolicySettings\Schemas\RefundPolicySettingInfolist;
use App\Filament\Resources\RefundPolicySettings\Tables\RefundPolicySettingsTable;
use App\Models\RefundPolicySetting;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RefundPolicySettingResource extends Resource
{
    protected static ?string $model = RefundPolicySetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Pages Settings';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RefundPolicySettingForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RefundPolicySettingInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefundPolicySettingsTable::configure($table);
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
            'index' => ListRefundPolicySettings::route('/'),
            'create' => CreateRefundPolicySetting::route('/create'),
            'view' => ViewRefundPolicySetting::route('/{record}'),
            'edit' => EditRefundPolicySetting::route('/{record}/edit'),
        ];
    }
}
