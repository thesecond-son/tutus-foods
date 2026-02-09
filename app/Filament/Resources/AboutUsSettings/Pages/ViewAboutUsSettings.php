<?php

namespace App\Filament\Resources\AboutUsSettings\Pages;

use App\Filament\Resources\AboutUsSettings\AboutUsSettingsResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAboutUsSettings extends ViewRecord
{
    protected static string $resource = AboutUsSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
