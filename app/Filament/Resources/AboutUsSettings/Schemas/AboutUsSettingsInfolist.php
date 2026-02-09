<?php

namespace App\Filament\Resources\AboutUsSettings\Schemas;

use Dom\Text;
use Filament\Infolists\Components\{
    TextEntry,
    RepeatableEntry,
    ImageEntry,
    IconEntry
};
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AboutUsSettingsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Section')
                    ->schema([
                        TextEntry::make('hero.title'),
                        TextEntry::make('hero.subtitle1'),
                        TextEntry::make('hero.subtitle2'),
                        TextEntry::make('hero.button_text'),
                        TextEntry::make('hero.button_link'),

                        RepeatableEntry::make('hero.images')
                            ->schema([
                                ImageEntry::make('image'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),
                Section::make('Philosophy Section')
                    ->schema([
                        TextEntry::make('philisophy.title'),
                        TextEntry::make('philisophy.description'),
                        TextEntry::make('philisophy.button_text'),
                        TextEntry::make('philisophy.button_link'),

                        ])
                    ->columnSpanFull(),

                Section::make('Tutus Family Section')
                    ->schema([
                        TextEntry::make('tutus_family.title'),
                        RepeatableEntry::make('tutus_family.items')
                            ->schema([
                                TextEntry::make('title'),
                                TextEntry::make('subtitle'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Our Story Section')
                    ->schema([
                        TextEntry::make('story.title'),
                        RepeatableEntry::make('story.items')
                            ->schema([
                                TextEntry::make('paragraph'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Join Us Section')
                    ->schema([
                        TextEntry::make('join_us.title'),
                        RepeatableEntry::make('join_us.items')
                            ->schema([
                                TextEntry::make('paragraph'),
                            ])
                            ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Customer Reviews Section')
                    ->schema([
                        TextEntry::make('reviews.title'),
                        RepeatableEntry::make('reviews.items')
                            ->schema([
                                TextEntry::make('review'),
                                TextEntry::make('name'),
                            ])
                            ->columns(3),
                    ])
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
