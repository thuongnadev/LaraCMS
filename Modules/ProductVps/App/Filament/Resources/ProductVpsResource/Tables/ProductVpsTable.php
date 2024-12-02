<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Media\Traits\ImageDisplay;
use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\Filters\ProductVpsFilter;
use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\Actions\ProductVpsAction;
use Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\BulkActions\ProductVpsBulkAction;

class ProductVpsTable
{
    use ImageDisplay;
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),
                self::getImageColumn('product_image', 'Hình ảnh', 50)()
                    ->square(),
                TextColumn::make('categories.name')
                    ->label('Danh mục')
                    ->formatStateUsing(fn($state, $record) => $record->categories->pluck('name')->join(', '))
                    ->sortable()
                    ->default('N/A'),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(ProductVpsFilter::filter())
            ->actions(ProductVpsAction::action())
            ->bulkActions(ProductVpsBulkAction::bulkActions())
            ->defaultSort('updated_at', 'desc');
    }
}
