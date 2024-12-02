<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Tables\Filters;

use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\Filter as FiltersFilter;
use Filament\Tables\Filters\SelectFilter;
use Tapp\FilamentValueRangeFilter\Filters\ValueRangeFilter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Entities\ProductVariant;

class ProductFilter
{
    public static function filter(): array
    {
        return [
            SelectFilter::make('status')
                ->label('Trạng thái')
                ->options([
                    'draft' => 'Nháp',
                    'published' => 'Đã xuất bản',
                    'archived' => 'Đã lưu trữ',
                ])
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        return $query->where('status', $data['value']);
                    }
                    return $query;
                }),
            FiltersFilter::make('has_variants')
                ->label('Sản phẩm có biến thể')
                ->query(function ($query) {
                    return $query->whereHas('productVariantOptions');
                }),
            SelectFilter::make('variant_id')
                ->label('Thuộc tính sản phẩm')
                ->options(
                    ProductVariant::all()->pluck('name', 'id')->toArray()
                )
                ->query(function ($query, array $data) {
                    if (isset($data['value'])) {
                        return $query->whereHas('productVariantOptions', function ($query) use ($data) {
                            return $query->where('variant_id', $data['value']);
                        });
                    }
                    return $query;
                }),
            ValueRangeFilter::make('price')
                ->label('Giá (VND)')
                ->currency()
                ->currencyCode('VND')
                ->locale('vi')
                ->query(function (Builder $query, array $data) {
                    return $query->whereHas('skus', function (Builder $query) use ($data) {
                        return $query
                            ->when(
                                $data['range_equal'],
                                fn(Builder $query, $value): Builder => $query->where('price', '=', $value)
                            )
                            ->when(
                                $data['range_not_equal'],
                                fn(Builder $query, $value): Builder => $query->where('price', '!=', $value)
                            )
                            ->when(
                                $data['range_between_from'] && $data['range_between_to'],
                                function (Builder $query) use ($data) {
                                    return $query->where('price', '>=', $data['range_between_from'])
                                        ->where('price', '<=', $data['range_between_to']);
                                }
                            )
                            ->when(
                                $data['range_greater_than'],
                                fn(Builder $query, $value): Builder => $query->where('price', '>', $value)
                            )
                            ->when(
                                $data['range_greater_than_equal'],
                                fn(Builder $query, $value): Builder => $query->where('price', '>=', $value)
                            )
                            ->when(
                                $data['range_less_than'],
                                fn(Builder $query, $value): Builder => $query->where('price', '<', $value)
                            )
                            ->when(
                                $data['range_less_than_equal'],
                                fn(Builder $query, $value): Builder => $query->where('price', '<=', $value)
                            );
                    });
                }),
        ];
    }
}
