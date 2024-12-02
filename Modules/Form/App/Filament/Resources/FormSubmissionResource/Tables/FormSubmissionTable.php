<?php

namespace Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables\Actions\FormSubmissionAction;
use Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables\BulkActions\FormSubmissionBulkAction;
use Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables\Filters\FormSubmissionFilter;

class FormSubmissionTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('form.name')
                    ->label('Tên Form')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->dateTime(),

                IconColumn::make('is_viewed')
                    ->label('Trạng thái xem')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),

                TextColumn::make('viewedByUser.name')
                    ->label('Người xem')
                    ->sortable(),

                TextColumn::make('viewed_at')
                    ->label('Thời gian xem')
                    ->dateTime(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(FormSubmissionFilter::filter())
            ->actions(FormSubmissionAction::action())
            ->bulkActions(FormSubmissionBulkAction::bulkActions());
    }
}
