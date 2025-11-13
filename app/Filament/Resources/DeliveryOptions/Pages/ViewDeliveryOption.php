<?php

namespace App\Filament\Resources\DeliveryOptions\Pages;

use App\Filament\Resources\DeliveryOptions\DeliveryOptionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDeliveryOption extends ViewRecord
{
    protected static string $resource = DeliveryOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
