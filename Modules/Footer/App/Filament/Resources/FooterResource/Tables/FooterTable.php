<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Footer\App\Filament\Resources\FooterResource\Tables\Actions\FooterAction;
use Modules\Footer\App\Filament\Resources\FooterResource\Tables\BulkActions\FooterBulkAction;
use Modules\Footer\App\Filament\Resources\FooterResource\Tables\Filters\FooterFilter;

class FooterTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên footer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('columns_count')
                    ->label('Số lượng cột')
                    ->counts('columns')
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
            ->actions(FooterAction::action())
            ->bulkActions(FooterBulkAction::bulkActions());
    }
}
