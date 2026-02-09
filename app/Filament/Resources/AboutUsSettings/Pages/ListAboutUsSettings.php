<?php

namespace App\Filament\Resources\AboutUsSettings\Pages;

use App\Filament\Resources\AboutUsSettings\AboutUsSettingsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAboutUsSettings extends ListRecords
{
    protected static string $resource = AboutUsSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
