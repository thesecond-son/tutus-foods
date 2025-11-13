<?php

namespace App\Filament\Resources\PickupLocations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PickupLocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Pickup Location Name')
                    ->required(),
                Textarea::make('address')
                    ->label('Address')
                    ->required()
                    ->columnSpanFull(),
                Select::make('available_days')
                    ->label('Available Days')
                    ->options([
                        'Sunday' => 'Sunday',
                        'Monday' => 'Monday',
                        'Tuesday' => 'Tuesday',
                        'Wednesday' => 'Wednesday',
                        'Thursday' => 'Thursday',
                        'Friday' => 'Friday',
                        'Saturday' => 'Saturday',
                    ])
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->helperText('Select the days customers can pick up orders at this location.')
                    ->columnSpanFull(),
                TimePicker::make('start_time')
                    ->label('Start Time')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('End Time')
                    ->required(),
                TextInput::make('interval_minutes')
                    ->required()
                    ->numeric()
                    ->default(30)
                    ->helperText('Set how often pickup slots are available (e.g., every 30 minutes).'),
            ]);
    }
}
