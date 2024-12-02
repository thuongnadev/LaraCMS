<?php

namespace Modules\ProductVps\App\Filament\Resources;

use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Forms\ProductVpsForm;
use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\ProductVpsTable;
use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\ProductVps\Entities\ProductVps as EntitiesProductVps;

class ProductVpsResource extends Resource
{
    protected static ?string $model = EntitiesProductVps::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Sản phẩm';
    }

    public static function getModelLabel(): string
    {
        return 'Sản phẩm';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Sản phẩm';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return ProductVpsForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ProductVpsTable::table($table);
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
            'index' => Pages\ListProductVps::route('/'),
            'create' => Pages\CreateProductVps::route('/create'),
            'edit' => Pages\EditProductVps::route('/{record}/edit'),
        ];
    }
}