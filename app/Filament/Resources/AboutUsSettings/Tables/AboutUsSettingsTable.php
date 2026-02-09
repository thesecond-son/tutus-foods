<?php

namespace App\Filament\Resources\AboutUsSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AboutUsSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hero.title')
                    ->label('Hero Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hero.subtitle1')
                    ->label('Hero Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hero.images')
                    ->label('Images')
                    ->state(fn ($record) => count($record->hero['images'] ?? []))
                    ->badge(),
                TextColumn::make('tutus_family')
                    ->label('Tutus Family Items')
                    ->state(fn ($record) => count($record->tutus_family ?? []))
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
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
