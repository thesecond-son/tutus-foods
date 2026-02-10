<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Pages;

use App\Filament\Resources\PrivacyPolicySettings\PrivacyPolicySettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrivacyPolicySetting extends ViewRecord
{
    protected static string $resource = PrivacyPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
