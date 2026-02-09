<?php

namespace App\Filament\Resources\AboutUsSettings\Pages;

use App\Filament\Resources\AboutUsSettings\AboutUsSettingsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAboutUsSettings extends EditRecord
{
    protected static string $resource = AboutUsSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
