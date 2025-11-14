<?php

namespace App\Filament\Resources\CorporateRequests\Pages;

use App\Filament\Resources\CorporateRequests\CorporateRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCorporateRequest extends EditRecord
{
    protected static string $resource = CorporateRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
