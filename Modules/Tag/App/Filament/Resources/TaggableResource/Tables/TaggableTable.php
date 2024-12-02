<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Tag\App\Filament\Resources\TaggableResource\Tables\Actions\TaggableAction;
use Modules\Tag\App\Filament\Resources\TaggableResource\Tables\BulkActions\TaggableBulkAction;
use Modules\Tag\App\Filament\Resources\TaggableResource\Tables\Filters\TaggableFilter;

class TaggableTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(TaggableFilter::filter())
            ->actions(TaggableAction::action())
            ->bulkActions(TaggableBulkAction::bulkActions());
    }
}