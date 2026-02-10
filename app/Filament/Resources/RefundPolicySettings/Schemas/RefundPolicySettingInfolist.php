<?php

namespace App\Filament\Resources\RefundPolicySettings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RefundPolicySettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('refund_policy.title')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('refund_policy.content')
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
