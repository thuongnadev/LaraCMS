<?php

namespace Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables\Actions\SettingCompanyAction;
use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables\BulkActions\SettingCompanyBulkAction;
use Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables\Filters\SettingCompanyFilter;

class SettingCompanyTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên doanh nghiệp')
                    ->sortable(),
                TextColumn::make('tax_code')
                    ->label('Mã số thuế')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters(SettingCompanyFilter::filter())
            ->actions(SettingCompanyAction::action())
            ->bulkActions(SettingCompanyBulkAction::bulkActions())
            ->defaultSort('updated_at', 'desc');
    }
}
