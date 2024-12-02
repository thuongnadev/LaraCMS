<?php

namespace Modules\SettingCompany\App\Filament\Resources\BranchResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Modules\SettingCompany\App\Filament\Resources\BranchResource\Tables\Actions\BranchAction;
use Modules\SettingCompany\App\Filament\Resources\BranchResource\Tables\BulkActions\BranchBulkAction;
use Modules\SettingCompany\App\Filament\Resources\BranchResource\Tables\Filters\BranchFilter;

class BranchTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Tên chi nhánh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Số điện thoại')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('Địa chỉ')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Trạng thái')
                    ->sortable()
                    ->onColor('success') // Màu xanh khi "on"
                    ->offColor('danger') // Màu đỏ khi "off"
                    ->onIcon('heroicon-o-check-circle') // Icon khi "on"
                    ->offIcon('heroicon-o-x-circle') // Icon khi "off"
                    ->action(function ($record, $state) {
                        // Cập nhật trạng thái dựa vào giá trị boolean $state
                        $record->status = $state ? '1' : '0';
                        $record->save(); // Lưu lại trạng thái mới vào cơ sở dữ liệu
                    }),
            ])
            ->filters(BranchFilter::filter())
            ->actions(BranchAction::action())
            ->bulkActions(BranchBulkAction::bulkActions());
    }
}
