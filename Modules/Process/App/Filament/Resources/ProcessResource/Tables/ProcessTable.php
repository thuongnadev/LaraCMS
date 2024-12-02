<?php

namespace Modules\Process\App\Filament\Resources\ProcessResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Process\App\Filament\Resources\ProcessResource\Tables\Actions\ProcessAction;
use Modules\Process\App\Filament\Resources\ProcessResource\Tables\BulkActions\ProcessBulkAction;
use Modules\Process\App\Filament\Resources\ProcessResource\Tables\Filters\ProcessFilter;

class ProcessTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên quy trình')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('steps_count')
                    ->label('Số bước')
                    ->counts('steps')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->actions(ProcessAction::action())
            ->bulkActions(ProcessBulkAction::bulkActions());
    }
}
