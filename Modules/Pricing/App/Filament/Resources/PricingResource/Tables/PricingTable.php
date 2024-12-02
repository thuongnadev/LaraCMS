<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Tables;

use Archilex\ToggleIconColumn\Columns\ToggleIconColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Pricing\App\Filament\Resources\PricingResource\Pages\CreatePricing;
use Modules\Pricing\App\Filament\Resources\PricingResource\Tables\Actions\PricingAction;
use Modules\Pricing\App\Filament\Resources\PricingResource\Tables\BulkActions\PricingBulkAction;
use Modules\Pricing\App\Filament\Resources\PricingResource\Tables\Filters\PricingFilter;

class PricingTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên bảng giá')
                    ->searchable(['name'])
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                TextColumn::make('pricingType.name')
                    ->label('Loại bảng')
                    ->searchable()
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->getStateUsing(fn($record) => $record->pricingType?->name ?? 'Chưa có')
                    ->sortable(),
                TextColumn::make('pricingGroup.name')
                    ->label('Nhóm bảng')
                    ->searchable()
                    ->alignCenter()
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                ToggleIconColumn::make('show')
                    ->label('Trạng thái')
                    ->tooltip(function ($record) {
                        return $record->show ? 'Hiển thị' : 'Ẩn';
                    })
                    ->onIcon('heroicon-o-eye')
                    ->offIcon('heroicon-o-eye-slash')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày thêm')
                    ->date('d/m/Y')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters(PricingFilter::filter())
            ->actions(PricingAction::action())
            ->emptyStateActions([
                Action::make('Tạo')
                    ->label('Tạo Bảng Giá')
                    ->url(CreatePricing::getUrl())
                    ->icon('heroicon-m-table-cells')
                    ->button(),
            ])
            ->bulkActions(PricingBulkAction::bulkActions());
    }
}
