<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Tables;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\Actions\PageMainAction;
use Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\BulkActions\PageMainBulkAction;
use Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\Filters\PageMainFilter;

class PageMainTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),
                ToggleIconColumn::make('is_active')
                    ->label('Kích hoạt')
                    ->tooltip(function ($record) {
                        return $record->show ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('published_time')
                    ->label('Thời gian xuất bản')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('modified_time')
                    ->label('Thời gian chỉnh sửa')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('author')
                    ->label('Tác giả')
                    ->searchable(),
                TextColumn::make('section')
                    ->label('Chuyên mục')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions(PageMainAction::action())
            ->bulkActions(PageMainBulkAction::bulkActions());
    }
}
