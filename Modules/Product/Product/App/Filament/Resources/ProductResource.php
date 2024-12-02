<?php

namespace Modules\Product\App\Filament\Resources;

use Modules\Product\App\Filament\Resources\ProductResource\Forms\ProductForm;
use Modules\Product\App\Filament\Resources\ProductResource\Tables\ProductTable;
use Modules\Product\Entities\Product;   
use Modules\Product\App\Filament\Resources\ProductResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    public static function getNavigationLabel(): string
    {
        return __('Sản phẩm');
    }

    public static function getModelLabel(): string
    {
        return __('Sản phẩm');
    }

    public static function getPluraModelLabel(): string
    {
        return __('Sản phẩm');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return ProductForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ProductTable::table($table);
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
            'index' => Pages\ListProduct::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
