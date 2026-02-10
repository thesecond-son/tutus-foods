<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Pages;

use App\Filament\Resources\PrivacyPolicySettings\PrivacyPolicySettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrivacyPolicySettings extends ListRecords
{
    protected static string $resource = PrivacyPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
