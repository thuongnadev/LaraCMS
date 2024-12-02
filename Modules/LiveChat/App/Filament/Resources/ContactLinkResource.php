<?php

namespace Modules\LiveChat\App\Filament\Resources;

use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Forms\ContactLinkForm;
use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\ContactLinkTable;
use Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\LiveChat\Entities\ContactLink;

class ContactLinkResource extends Resource
{
    protected static ?string $model = ContactLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';
    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getNavigationLabel(): string
    {
        return __('Nút liên hệ');
    }

    public static function getModelLabel(): string
    {
        return __('Nút liên hệ');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Nút liên hệ');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return ContactLinkForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ContactLinkTable::table($table);
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
            'index' => Pages\ListContactLink::route('/'),
            'create' => Pages\CreateContactLink::route('/create'),
            'edit' => Pages\EditContactLink::route('/{record}/edit'),
        ];
    }
}