<?php

namespace App\Filament\Resources\RefundPolicySettings\Pages;

use App\Filament\Resources\RefundPolicySettings\RefundPolicySettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRefundPolicySetting extends ViewRecord
{
    protected static string $resource = RefundPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
