<?php

namespace App\Filament\Resources\HomepageSettings\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\{
    TextEntry,
    RepeatableEntry,
    ImageEntry,
    IconEntry
};
use Filament\Schemas\Schema;

class HomepageSettingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
        Section::make('Hero Section')
            ->schema([
                TextEntry::make('hero.title'),
                TextEntry::make('hero.subtitle'),
                TextEntry::make('hero.button_text'),
                TextEntry::make('hero.button_link'),

                RepeatableEntry::make('hero.slides')
                    ->schema([
                        ImageEntry::make('image'),
                    ])
                    ->columns(3),
            ])
            ->columnSpanFull(),
        Section::make('Favourites')
            ->schema([
                RepeatableEntry::make('favourites')
                    ->schema([
                        ImageEntry::make('image')
                        ->disk('public'),
                        TextEntry::make('title'),
                        TextEntry::make('url'),
                    ])
                    ->columns(3),
            ])
            ->columnSpanFull(),
        
        Section::make('Signature Brands')
            ->schema([
                RepeatableEntry::make('signature_brands')
                    ->label('Brands')
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->height(150),

                        TextEntry::make('title'),

                        TextEntry::make('description'),

                        TextEntry::make('slug')
                            ->label('Category Slug'),

                        IconEntry::make('is_live')
                            ->boolean()
                            ->label('Live'),
                    ])
                    ->columns(2),
            ])
            ->columnSpanFull(),
            
        // CHRISTMAS SECTION
        Section::make('Christmas Shop Section')
            ->schema([
                IconEntry::make('christmas_section.enabled')
                    ->label('Show Christmas Section')
                    ->boolean(),

                TextEntry::make('christmas_section.title')
                    ->label('Section Title')
                    ->visible(fn ($record) => $record->christmas_section['enabled'] ?? false),

                TextEntry::make('christmas_section.description')
                    ->label('Description')
                    ->visible(fn ($record) => $record->christmas_section['enabled'] ?? false),

                TextEntry::make('christmas_section.button_text')
                    ->label('Button Text')
                    ->visible(fn ($record) => $record->christmas_section['enabled'] ?? false),

                TextEntry::make('christmas_section.button_link')
                    ->label('Button Link')
                    ->visible(fn ($record) => $record->christmas_section['enabled'] ?? false),
            ])
            ->columnSpanFull()
            ->collapsible(),

        Section::make('Order Cut-Off Times')
            ->schema([
                RepeatableEntry::make('order_cutoff_times')
                    ->schema([
                        TextEntry::make('pickup_day')
                            ->label('Pickup Day'),

                        TextEntry::make('order_by')
                            ->label('Order By'),
                    ])
                    ->columns(2),
            ])
            ->columnSpanFull(),
        Section::make('Footer')
            ->schema([
                ImageEntry::make('footer.image')
                    ->disk('public')
                    ->visibility('public')
                    ->label('Footer Logo'),
                TextEntry::make('footer.phone')
                    ->label('Phone'),

                TextEntry::make('footer.email')
                    ->label('Email'),

                TextEntry::make('footer.address')
                    ->label('Address'),

                RepeatableEntry::make('footer.opening_hours')
                    ->label('Opening Hours')
                    ->schema([
                        TextEntry::make('days'),

                        TextEntry::make('time'),
                    ])
                    ->columns(2),
            ])
            ->columnSpanFull(),
        Section::make('Meta')
            ->schema([
                TextEntry::make('created_at')
                    ->dateTime(),

                TextEntry::make('updated_at')
                    ->dateTime(),
            ])
            ->columns(2)
            ->columnSpanFull(),
            ]);
    }
}
