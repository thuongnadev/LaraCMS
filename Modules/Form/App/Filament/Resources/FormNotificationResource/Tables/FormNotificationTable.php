<?php

namespace Modules\Form\App\Filament\Resources\FormNotificationResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Form\App\Filament\Resources\FormNotificationResource\Tables\Actions\FormNotificationAction;
use Modules\Form\App\Filament\Resources\FormNotificationResource\Tables\BulkActions\FormNotificationBulkAction;
use Modules\Form\App\Filament\Resources\FormNotificationResource\Tables\Filters\FormNotificationFilter;

class FormNotificationTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('form.name')
                    ->label('Tên biểu mẫu thông báo')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('success_message')
                    ->label('Thông báo thành công')
                    ->wrap()
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('error_message')
                    ->label('Thông báo lỗi')
                    ->wrap()
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            
            ])
            ->defaultSort('created_at', 'desc')
            ->filters(FormNotificationFilter::filter())
            ->actions(FormNotificationAction::action())
            ->bulkActions(FormNotificationBulkAction::bulkActions());
    }
}