<?php

namespace App\Filament\Resources\NewsletterSubscribers;

use App\Filament\Resources\NewsletterSubscribers\Pages\CreateNewsletterSubscribers;
use App\Filament\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscribers;
use App\Filament\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use App\Filament\Resources\NewsletterSubscribers\Pages\ViewNewsletterSubscribers;
use App\Filament\Resources\NewsletterSubscribers\Schemas\NewsletterSubscribersForm;
use App\Filament\Resources\NewsletterSubscribers\Schemas\NewsletterSubscribersInfolist;
use App\Filament\Resources\NewsletterSubscribers\Tables\NewsletterSubscribersTable;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NewsletterSubscribersResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'Newsletter Management';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return NewsletterSubscribersForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NewsletterSubscribersInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NewsletterSubscribersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNewsletterSubscribers::route('/'),
            'create' => CreateNewsletterSubscribers::route('/create'),
            'view' => ViewNewsletterSubscribers::route('/{record}'),
            'edit' => EditNewsletterSubscribers::route('/{record}/edit'),
        ];
    }
    
    //Access Management
    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
}
