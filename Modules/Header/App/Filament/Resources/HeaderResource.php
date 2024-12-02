<?php

namespace Modules\Header\App\Filament\Resources;

use Modules\Header\App\Filament\Resources\HeaderResource\Forms\HeaderForm;
use Modules\Header\App\Filament\Resources\HeaderResource\Tables\HeaderTable;
use Modules\Header\Entities\Header;
use Modules\Header\App\Filament\Resources\HeaderResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class HeaderResource extends Resource
{
    protected static ?string $model = Header::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Cấu hình web';


    public static function getNavigationLabel(): string
    {
        return 'Header';
    }

    public static function getModelLabel(): string
    {
        return 'Header';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Header';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return HeaderForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return HeaderTable::table($table);
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
            'index' => Pages\ListHeader::route('/'),
            'create' => Pages\CreateHeader::route('/create'),
            'edit' => Pages\EditHeader::route('/{record}/edit'),
        ];
    }
}
