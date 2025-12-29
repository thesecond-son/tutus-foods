<?php

namespace App\Filament\Resources\HomepageSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomepageSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hero.title')
                    ->label('Hero Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hero.slides')
                    ->label('Slides')
                    ->state(fn ($record) => count($record->hero['slides'] ?? []))
                    ->badge(),
                TextColumn::make('favourites')
                    ->label('Favourites')
                    ->state(fn ($record) => count($record->favourites ?? []))
                    ->badge(),
                TextColumn::make('signature_brands')
                    ->label('Brands')
                    ->state(fn ($record) => count($record->signature_brands ?? []))
                    ->badge(),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
