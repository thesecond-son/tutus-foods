<?php

namespace App\Filament\Resources\CheckoutPageSettings\Pages;

use App\Filament\Resources\CheckoutPageSettings\CheckoutPageSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckoutPageSetting extends EditRecord
{
    protected static string $resource = CheckoutPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
