<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Pages;

use App\Filament\Resources\PrivacyPolicySettings\PrivacyPolicySettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrivacyPolicySetting extends CreateRecord
{
    protected static string $resource = PrivacyPolicySettingResource::class;
}
