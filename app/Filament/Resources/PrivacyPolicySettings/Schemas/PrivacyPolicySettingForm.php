<?php

namespace App\Filament\Resources\PrivacyPolicySettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{
    TextInput,
    RichEditor,
};
class PrivacyPolicySettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Privacy Policy')
                    ->schema([
                        TextInput::make('privacy_policy.title')
                            ->label('Page Title')
                            ->required(),

                        RichEditor::make('privacy_policy.content')
                            ->label('Page Content')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'bulletList',
                                'orderedList',
                                'link',
                                'blockquote',
                            ])
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
