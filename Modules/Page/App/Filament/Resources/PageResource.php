<?php

namespace Modules\Page\App\Filament\Resources;

use Modules\Page\App\Filament\Resources\PageResource\Forms\PageForm;
use Modules\Page\App\Filament\Resources\PageResource\Tables\PageTable;
use Modules\Page\Entities\Page;
use Modules\Page\App\Filament\Resources\PageResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationGroup = 'Cấu hình web';

    public static function getNavigationLabel(): string
    {
        return 'Trang';
    }

    public static function getModelLabel(): string
    {
        return 'Trang';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Trang';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return PageForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return PageTable::table($table);
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
            'index' => Pages\ListPage::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
