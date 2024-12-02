<?php

namespace Modules\Page\App\Filament\Resources\PageResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Page\App\Filament\Resources\PageResource\Tables\Actions\PageAction;
use Modules\Page\App\Filament\Resources\PageResource\Tables\BulkActions\PageBulkAction;
use Modules\Page\App\Filament\Resources\PageResource\Tables\Filters\PageFilter;

class PageTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at' ,'desc')
            ->filters(PageFilter::filter())
            ->actions(PageAction::action())
            ->bulkActions(PageBulkAction::bulkActions());
    }
}