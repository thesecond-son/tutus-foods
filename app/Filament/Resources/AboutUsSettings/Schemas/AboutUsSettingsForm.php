<?php

namespace App\Filament\Resources\AboutUsSettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Repeater,
    FileUpload,
    Toggle
};

class AboutUsSettingsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hero Section')
                ->schema([
                    TextInput::make('hero.title')
                        ->label('Hero Title')
                        ->required(),

                    Textarea::make('hero.subtitle1')
                        ->label('Hero Subtitle One'),

                    Textarea::make('hero.subtitle2')
                        ->label('Hero Subtitle Two'),

                    TextInput::make('hero.button_text')
                        ->label('Button Text'),

                    TextInput::make('hero.button_link')
                        ->label('Button Link')
                        ->url(),

                    Repeater::make('hero.images')
                        ->label('Hero Images')
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('homepage/hero')
                                ->imagePreviewHeight(120)
                                ->visibility('public')
                                ->required(),
                            // FileUpload::make('secondary_image')
                            //     ->image()
                            //     ->disk('public')
                            //     ->directory('homepage/hero')
                            //     ->imagePreviewHeight(120)
                            //     ->visibility('public')
                            //     ->required(),
                        ])
                        ->minItems(2)
                        ->maxItems(2)
                        ->columns(2),
                ])
                ->columnSpanFull(),

                Section::make('Our Philosophy')
                    ->schema([
                    TextInput::make('philisophy.title')
                        ->label('Philosophy Title')
                        ->required(),

                    Textarea::make('philisophy.description')
                        ->label('Philosophy Description')
                        ->rows(4)
                        ->required(),

                    TextInput::make('philisophy.button_text')
                        ->label('Button Text'),

                    TextInput::make('philisophy.button_link')
                        ->url()
                        ->required(),
                    ])
                    ->columnSpanFull(),

                Section::make('Tutus Family')
                    ->schema([
                        TextInput::make('tutus_family.title')
                            ->label('Section Title')
                            ->required(),
                        Repeater::make('tutus_family.items')
                            ->label('Tutus Family Categories')
                            ->schema([
                                TextInput::make('title')
                                ->required(),
                                Textarea::make('subtitle')
                                ->rows(3)
                                ->required(),
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Our Story')
                    ->schema([
                        TextInput::make('story.title')
                            ->label('Section Title')
                            ->required(),
                        Repeater::make('story.items')
                            ->label('Section Paragraphs')
                            ->schema([
                                Textarea::make('paragraph')
                                    ->rows(3)
                                    ->required(),
                        ])
                        ->columns(3),
                    ])
                    ->columnSpanFull(),

                Section::make('Join Us')
                    ->schema([
                        TextInput::make('join_us.title')
                            ->label('Section Title')
                            ->required(),
                        Repeater::make('join_us.items')
                            ->label('Join Us Section')
                            ->schema([
                                Textarea::make('paragraph')
                                    ->required(),
                        ])
                        ->columns(3),
                    ])
                    ->columnSpanFull(),

                // Textarea::make('info')
                //     ->default(null)
                //     ->columnSpanFull(),
                
                Section::make('Customer Reviews')
                    ->schema([
                        TextInput::make('reviews.title')
                            ->label('Section Title')
                            ->required(),
                        Repeater::make('reviews.items')
                            ->label('Reviews')
                            ->schema([
                                Textarea::make('review')
                                    ->required(),
                                TextInput::make('name')
                                     ->label('Reviewer Name')
                                    ->required(),
                        ])
                        ->columns(3),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
