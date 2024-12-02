<?php

namespace Modules\Form\App\Filament\Resources\FormResource\Tables;

use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Form\App\Filament\Resources\FormResource\Tables\Actions\FormAction;
use Modules\Form\App\Filament\Resources\FormResource\Tables\BulkActions\FormBulkAction;
use Modules\Form\App\Filament\Resources\FormResource\Tables\Filters\FormFilter;

class FormTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên biểu mẫu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(FormFilter::filter())
            ->actions([
                Action::make('emailSetting')
                    ->label('Cấu hình Email')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->url(fn($record) => route('filament.admin.resources.email-settings.create', ['form_id' => $record->id]))
                    ->openUrlInNewTab()
                    ->button()
                    ->visible(fn($record) => $record->emailSetting === null),
                ...(FormAction::action()),
            ])
            ->bulkActions(FormBulkAction::bulkActions())
            ->defaultSort('updated_at' ,'desc');
    }
}
