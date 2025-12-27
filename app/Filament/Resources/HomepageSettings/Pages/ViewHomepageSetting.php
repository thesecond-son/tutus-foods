<?php

namespace App\Filament\Resources\HomepageSettings\Pages;

use App\Filament\Resources\HomepageSettings\HomepageSettingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewHomepageSetting extends ViewRecord
{
    protected static string $resource = HomepageSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
