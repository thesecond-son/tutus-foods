<?php

namespace App\Filament\Resources\RefundPolicySettings\Pages;

use App\Filament\Resources\RefundPolicySettings\RefundPolicySettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRefundPolicySetting extends EditRecord
{
    protected static string $resource = RefundPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
