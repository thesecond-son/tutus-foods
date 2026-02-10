<?php

namespace App\Filament\Resources\RefundPolicySettings\Pages;

use App\Filament\Resources\RefundPolicySettings\RefundPolicySettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRefundPolicySettings extends ListRecords
{
    protected static string $resource = RefundPolicySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
