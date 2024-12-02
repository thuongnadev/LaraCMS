<?php

namespace Modules\Category\App\Filament\Resources\CategoryResource\Tables;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\Actions\CategoryAction;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\BulkActions\CategoryBulkAction;
use Modules\Category\App\Filament\Resources\CategoryResource\Tables\Filters\CategoryFilter;

class CategoryTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Danh mục')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('parent.name')
                    ->label('Danh mục cha')
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->parent ? $record->parent->name : 'Danh mục gốc';
                    }),
                TextColumn::make('category_type')
                    ->label('Loại danh mục')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        return $record->category_type === 'product' ? 'Danh mục sản phẩm' : 'Danh mục bài viết';
                    }),
                ToggleIconColumn::make('status')
                    ->label('Trạng thái')
                    ->tooltip(function ($record) {
                        return $record->status ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(CategoryFilter::filter())
            ->actions(CategoryAction::action())
            ->bulkActions(CategoryBulkAction::bulkActions());
    }
}
