<?php

namespace Modules\Footer\App\Filament\Resources;

use Modules\Footer\App\Filament\Resources\FooterResource\Forms\FooterForm;
use Modules\Footer\App\Filament\Resources\FooterResource\Tables\FooterTable;
use Modules\Footer\Entities\Footer;
use Modules\Footer\App\Filament\Resources\FooterResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class FooterResource extends Resource
{
    protected static ?string $model = Footer::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getNavigationLabel(): string
    {
        return 'Footer';
    }

    public static function getModelLabel(): string
    {
        return 'Footer';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Footer';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return FooterForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return FooterTable::table($table);
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
            'index' => Pages\ListFooter::route('/'),
            'create' => Pages\CreateFooter::route('/create'),
            'edit' => Pages\EditFooter::route('/{record}/edit'),
        ];
    }
}
