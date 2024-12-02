<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Product\App\Filament\Resources\ProductResource\Tables\Actions\ProductAction;
use Modules\Product\App\Filament\Resources\ProductResource\Tables\BulkActions\ProductBulkAction;
use Modules\Product\App\Filament\Resources\ProductResource\Tables\Filters\ProductFilter;
use NumberFormatter;

class ProductTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Tên sản phẩm')
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('skus.price')
                    ->label('Giá sản phẩm')
                    ->formatStateUsing(function ($state) {
                        $formatSingle = function ($price) {
                            $price = floatval($price);
                            $formatter = new NumberFormatter('vi_VN', NumberFormatter::CURRENCY);
                            $formattedPrice = $formatter->formatCurrency($price, 'VND');
                            $formattedPrice = str_replace('₫', '', $formattedPrice);
                            return trim($formattedPrice) . ' VND';
                        };
                        $prices = explode(',', $state);
                        $formattedPrices = array_map(function ($price) use ($formatSingle) {
                            return $formatSingle(trim($price));
                        }, $prices);
                        return implode(', ', $formattedPrices);
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('skus.stock')
                    ->label('Tồn kho')
                    ->formatStateUsing(function ($state) {
                        $formatSingle = function ($stock) {
                            $stock = intval($stock);
                            $formatter = new NumberFormatter('vi_VN', NumberFormatter::DECIMAL);
                            $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 0);
                            $formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
                            $formattedStock = $formatter->format($stock);
                            return $formattedStock . ' sản phẩm';
                        };
                        $stocks = explode(',', $state);
                        $formattedStocks = array_map(function ($stock) use ($formatSingle) {
                            return $formatSingle(trim($stock));
                        }, $stocks);

                        return implode(', ', $formattedStocks);
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'archived' => 'warning',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'draft' => 'Nháp',
                        'published' => 'Xuất bản',
                        'archived' => 'Lưu trữ',
                        default => ucfirst($state),
                    })
                    ->icon(fn(string $state): ?string => match ($state) {
                        'draft' => 'heroicon-s-pencil-square',
                        'published' => 'heroicon-s-check-circle',
                        'archived' => 'heroicon-s-archive-box',
                        default => null,
                    })
                    ->tooltip(fn(string $state): ?string => match ($state) {
                        'draft' => 'Sản phẩm hiện đang là bản nháp.',
                        'published' => 'Sản phẩm này đã được xuất bản.',
                        'archived' => 'Sản phẩm này đã được lưu trữ.',
                        default => null,
                    })
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
            ->defaultSort('updated_at', 'desc')
            ->filters(ProductFilter::filter())
            ->actions(ProductAction::action())
            ->bulkActions(ProductBulkAction::bulkActions());
    }
}
