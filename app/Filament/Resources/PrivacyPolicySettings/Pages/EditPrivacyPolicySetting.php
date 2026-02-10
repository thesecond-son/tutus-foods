<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Pages;

use App\Filament\Resources\PrivacyPolicySettings\PrivacyPolicySettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPrivacyPolicySetting extends EditRecord
{
    protected static string $resource = PrivacyPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
