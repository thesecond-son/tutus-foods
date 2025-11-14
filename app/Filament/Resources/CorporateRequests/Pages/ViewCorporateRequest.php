<?php

namespace App\Filament\Resources\CorporateRequests\Pages;

use App\Filament\Resources\CorporateRequests\CorporateRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCorporateRequest extends ViewRecord
{
    protected static string $resource = CorporateRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
