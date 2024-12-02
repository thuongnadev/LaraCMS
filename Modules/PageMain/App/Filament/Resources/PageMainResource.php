<?php

namespace Modules\PageMain\App\Filament\Resources;

use Modules\PageMain\App\Filament\Resources\PageMainResource\Forms\PageMainForm;
use Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\PageMainTable;
use Modules\PageMain\App\Filament\Resources\PageMainResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\PageMain\Entities\PageMain;

class PageMainResource extends Resource
{
    protected static ?string $model = PageMain::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getNavigationLabel(): string
    {
        return 'Cấu hình trang';
    }

    public static function getModelLabel(): string
    {
        return 'Cấu hình trang';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Cấu hình trang';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return PageMainForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return PageMainTable::table($table);
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
            'index' => Pages\ListPageMain::route('/'),
            'create' => Pages\CreatePageMain::route('/create'),
            'edit' => Pages\EditPageMain::route('/{record}/edit'),
        ];
    }
}