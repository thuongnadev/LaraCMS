<?php

namespace Modules\Component\App\Filament\Resources\ComponentResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Component\App\Filament\Resources\ComponentResource\Tables\Actions\ComponentAction;
use Modules\Component\App\Filament\Resources\ComponentResource\Tables\BulkActions\ComponentBulkAction;
use Modules\Component\App\Filament\Resources\ComponentResource\Tables\Filters\ComponentFilter;

class ComponentTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên thành phần')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(ComponentFilter::filter())
            ->actions(ComponentAction::action())
            ->bulkActions(ComponentBulkAction::bulkActions());
    }
}
