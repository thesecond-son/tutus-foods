<?php

namespace App\Filament\Resources\RefundPolicySettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\{
    TextInput,
    RichEditor,
};

class RefundPolicySettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Refund Policy')
                    ->schema([
                        TextInput::make('refund_policy.title')
                            ->label('Page Title')
                            ->required(),

                        RichEditor::make('refund_policy.content')
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
