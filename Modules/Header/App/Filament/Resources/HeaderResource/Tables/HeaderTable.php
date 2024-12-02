<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Header\App\Filament\Resources\HeaderResource\Tables\Actions\HeaderAction;
use Modules\Header\App\Filament\Resources\HeaderResource\Tables\BulkActions\HeaderBulkAction;

class HeaderTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên header')
                    ->searchable()
                    ->sortable(),

                ImageColumn::make('logo')
                    ->label('Hình ảnh logo'),

                TextColumn::make('contacts_count')
                    ->label('Số lượng liên hệ')
                    ->counts('contacts')
                    ->sortable()
                    ->badge('primary')
                    ->alignCenter(),

                IconColumn::make('status')
                    ->label('Trạng thái')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions(HeaderAction::action())
            ->bulkActions(HeaderBulkAction::bulkActions());
    }
}
