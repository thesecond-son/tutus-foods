<?php

namespace App\Filament\Resources\CorporateRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CorporateRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('phone')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                TextColumn::make('purpose')
                    ->searchable(),
                TextColumn::make('people')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
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
                EditAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->hasRole('admin')),
                ]),
            ]);
    }
}
