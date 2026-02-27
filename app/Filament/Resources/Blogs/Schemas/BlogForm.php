<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->reactive() // this makes it update in real-time
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Generate slug from the title automatically
                        $set('slug', Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('blog-images')
                    ->preserveFilenames(),
                RichEditor::make('content')->required()->toolbarButtons([
                        'bold', 'italic', 'link', 'h1', 'h2', 'h3', 'bulletList', 'orderedList', 'blockquote'
                ]), 
                TextInput::make('read_length')
                    ->numeric()
                    ->label('Read Time (minutes)')
                    ->default(null),
                DatePicker::make('posted_at')->label('Date Posted')->default(now()),
                Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->preload(),
                TextInput::make('likes')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('share_link')
                    ->default(null),
            ]);
    }
}
