<?php

namespace App\Filament\Resources\CheckoutPageSettings\Pages;

use App\Filament\Resources\CheckoutPageSettings\CheckoutPageSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCheckoutPageSetting extends ViewRecord
{
    protected static string $resource = CheckoutPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
