<?php

namespace Modules\Form\App\Filament\Resources\EmailSettingResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Form\App\Filament\Resources\EmailSettingResource\Tables\Actions\EmailSettingAction;
use Modules\Form\App\Filament\Resources\EmailSettingResource\Tables\BulkActions\EmailSettingBulkAction;
use Modules\Form\App\Filament\Resources\EmailSettingResource\Tables\Filters\EmailSettingFilter;

class EmailSettingTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('form.name')
                    ->label('Tên biểu mẫu')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('to_email')
                    ->label('Mail nhận'),
                TextColumn::make('from_email')
                    ->label('Mail gửi'),
                TextColumn::make('subject')
                    ->label('Tiêu đề'),
                TextColumn::make('created_at')
                    ->label('Ngày gửi')
                    ->date('d/m/Y'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(EmailSettingFilter::filter())
            ->actions(EmailSettingAction::action())
            ->bulkActions(EmailSettingBulkAction::bulkActions());
    }
}
