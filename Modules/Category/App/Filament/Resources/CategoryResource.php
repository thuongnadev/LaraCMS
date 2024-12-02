<?php

namespace Modules\Category\App\Filament\Resources;

use Modules\Category\App\Filament\Resources\CategoryResource\Forms\CategoryForm;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\CategoryTable;
use Modules\Category\App\Filament\Resources\CategoryResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Category\Entities\Category;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Danh mục';
    }

    public static function getModelLabel(): string
    {
        return 'Danh mục';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Danh mục';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return CategoryForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return CategoryTable::table($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategory::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}