<?php

namespace App\Filament\Resources\PickupLocations\Pages;

use App\Filament\Resources\PickupLocations\PickupLocationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPickupLocation extends ViewRecord
{
    protected static string $resource = PickupLocationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
