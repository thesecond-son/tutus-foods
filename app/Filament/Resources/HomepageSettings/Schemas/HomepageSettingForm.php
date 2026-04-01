<?php

namespace App\Filament\Resources\HomepageSettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Repeater,
    FileUpload,
    Toggle
};

class HomepageSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hero Section')
                ->schema([
                    TextInput::make('hero.title')
                        ->label('Hero Title')
                        ->required(),

                    Textarea::make('hero.subtitle')
                        ->label('Hero Subtitle'),

                    TextInput::make('hero.button_text')
                        ->label('Button Text'),

                    TextInput::make('hero.button_link')
                        ->label('Button Link')
                        ->url(),

                    Repeater::make('hero.slides')
                        ->label('Hero Slides')
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('homepage/hero')
                                ->imagePreviewHeight(120)
                                ->visibility('public')
                                ->required(),
                    TextInput::make('image_alt')
                                ->label('Image Alt Text')
                                ->placeholder('e.g., Chocolate cake with frosting')
                                ->maxLength(255)
                                ->columnSpanFull(),       
                        ])
                        ->minItems(3)
                        ->maxItems(3)
                        ->columns(3),
                ])
                ->columnSpanFull(),

            Section::make('Favourites')
                ->schema([
                    Repeater::make('favourites')
                        ->label('Favourite Categories')
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('homepage/favourites')
                                ->visibility('public')
                                ->imagePreviewHeight(100)
                                ->required(),

                            TextInput::make('image_alt')
                                ->label('Image Alt Text')
                                ->placeholder('e.g., Chocolate cake with frosting')
                                ->maxLength(255)
                                ->columnSpanFull(), 

                            TextInput::make('title')
                                ->required(),

                            TextInput::make('url')
                                ->url()
                                ->required(),
                        ])
                        ->columns(3)
                        ->defaultItems(3),
                ])
                ->columnSpanFull(),

            Section::make('Signature Brands')
                ->schema([
                    Repeater::make('signature_brands')
                        ->itemLabel(fn ($state) => $state['title'] ?? 'Brand')
                        ->schema([
                            FileUpload::make('image')
                                ->image()
                                ->disk('public')
                                ->directory('homepage/brands')
                                ->visibility('public')
                                ->imagePreviewHeight(150)
                                ->required(),

                            TextInput::make('image_alt')
                                ->label('Image Alt Text')
                                ->placeholder('e.g., Chocolate cake with frosting')
                                ->maxLength(255)
                                ->columnSpanFull(), 

                            TextInput::make('title')
                                ->required(),

                            Textarea::make('description'),

                            TextInput::make('slug')
                                ->helperText('Used for category URL'),

                            Toggle::make('is_live')
                                ->default(true),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),

            // CHRISTMAS SECTION
            Section::make('Christmas Shop Section')
                ->schema([
                    Toggle::make('christmas_section.enabled')
                        ->label('Show Christmas Section')
                        ->default(false)
                        ->live(),

                    TextInput::make('christmas_section.title')
                        ->label('Section Title')
                        ->default('Celebrate the Season with Tutu\'s Foods')
                        ->visible(fn ($get) => $get('christmas_section.enabled')),

                    Textarea::make('christmas_section.description')
                        ->label('Description')
                        ->default('Discover handcrafted festive treats, seasonal delights, and holiday specials to make your Christmas extra joyful and delicious.')
                        ->visible(fn ($get) => $get('christmas_section.enabled')),

                    TextInput::make('christmas_section.button_text')
                        ->label('Button Text')
                        ->default('Shop Christmas Collection')
                        ->visible(fn ($get) => $get('christmas_section.enabled')),

                    TextInput::make('christmas_section.button_link')
                        ->label('Button Link')
                        ->default('shop-christmas')
                        ->visible(fn ($get) => $get('christmas_section.enabled')),
                ])
                ->columnSpanFull()
                ->collapsible(),
            
            //ORDER CUT-OFF TIMES
            Section::make('Order Cut-Off Times')
                ->schema([
                    TextInput::make('subtitle')
                    ->label('Section Subtitle')
                    ->required(),

                    Repeater::make('order_cutoff_times')
                        ->schema([
                           TextInput::make('pickup_day')
                                ->required(),

                            TextInput::make('order_by')
                                ->required(),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),

            Section::make('Footer')
                ->schema([
                    FileUpload::make('footer.image')
                        ->label('Footer Logo')
                        ->image()
                        ->disk('public')
                        ->directory('homepage/footer')
                        ->visibility('public')
                        ->imagePreviewHeight(80),
                    
                    TextInput::make('footer.image_alt')
                        ->label('Image Alt Text')
                        ->placeholder('e.g., Chocolate cake with frosting')
                        ->maxLength(255)
                        ->columnSpanFull(), 

                    TextInput::make('footer.phone'),
                    TextInput::make('footer.email')->email(),
                    TextInput::make('footer.address'),

                    Repeater::make('footer.opening_hours')
                        ->schema([
                            TextInput::make('days'),
                            TextInput::make('time'),
                        ])
                        ->columns(2),
                ])
                ->columnSpanFull(),
        ]);
    }
}
