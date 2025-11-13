<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('content')
                    ->columnSpanFull(),
                TextEntry::make('read_length')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('posted_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('likes')
                    ->numeric(),
                TextEntry::make('share_link')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
