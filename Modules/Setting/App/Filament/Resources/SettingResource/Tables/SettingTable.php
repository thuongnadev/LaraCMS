<?php

namespace Modules\Setting\App\Filament\Resources\SettingResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Setting\App\Filament\Resources\SettingResource\Tables\Actions\SettingAction;
use Modules\Setting\App\Filament\Resources\SettingResource\Tables\BulkActions\SettingBulkAction;
use Modules\Setting\App\Filament\Resources\SettingResource\Tables\Filters\SettingFilter;

class SettingTable
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
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(SettingFilter::filter())
            ->actions(SettingAction::action())
            ->bulkActions(SettingBulkAction::bulkActions());
    }
}