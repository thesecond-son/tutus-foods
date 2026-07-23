<?php

namespace App\Filament\Resources\CheckoutPageSettings\Pages;

use App\Filament\Resources\CheckoutPageSettings\CheckoutPageSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCheckoutPageSettings extends ListRecords
{
    protected static string $resource = CheckoutPageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
