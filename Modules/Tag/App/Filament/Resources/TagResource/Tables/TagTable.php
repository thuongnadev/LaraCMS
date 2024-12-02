<?php

namespace Modules\Tag\App\Filament\Resources\TagResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Tag\App\Filament\Resources\TagResource\Tables\Actions\TagAction;
use Modules\Tag\App\Filament\Resources\TagResource\Tables\BulkActions\TagBulkAction;
use Modules\Tag\App\Filament\Resources\TagResource\Tables\Filters\TagFilter;

class TagTable
{
    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên thẻ')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('posts_count')
                    ->label('Số lượng bài viết')
                    ->counts('posts')
                    ->color('gray')
                    ->badge()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('products_count')
                    ->label('Số lượng sản phẩm')
                    ->counts('products')
                    ->color('gray')
                    ->badge()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
               
            ])
            ->filters(TagFilter::filter())
            ->actions(TagAction::action())
            ->bulkActions(TagBulkAction::bulkActions());
    }
}
