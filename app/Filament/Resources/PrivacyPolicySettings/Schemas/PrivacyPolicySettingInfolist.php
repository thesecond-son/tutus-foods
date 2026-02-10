<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PrivacyPolicySettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('privacy_policy.title')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('privacy_policy.content')
                    ->html()
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
