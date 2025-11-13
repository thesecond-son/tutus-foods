<?php

namespace App\Filament\Resources\PickupLocations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PickupLocationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                    TextColumn::make('available_days')
                    ->label('Available Days')
                    ->formatStateUsing(function ($state) {
                        // Handle null
                        if (empty($state)) {
                            return '-';
                        }

                        // If it's already an array
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }

                        // If it's a JSON string
                        $decoded = json_decode($state, true);
                        if (is_array($decoded)) {
                            return implode(', ', $decoded);
                        }

                        // If it's something else (fallback)
                        return (string) $state;
                    })
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('interval_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
