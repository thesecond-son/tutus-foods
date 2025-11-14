<?php

namespace App\Filament\Resources\CorporateRequests\Pages;

use App\Filament\Resources\CorporateRequests\CorporateRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCorporateRequests extends ListRecords
{
    protected static string $resource = CorporateRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
