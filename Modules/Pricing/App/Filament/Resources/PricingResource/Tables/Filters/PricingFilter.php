<?php

namespace Modules\Pricing\App\Filament\Resources\PricingResource\Tables\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class PricingFilter
{
    public static function filter(): array
    {
        return [
            Filter::make('created_at')
                ->label('Ngày tạo')
                ->form([
                    DatePicker::make('created_from')
                        ->label('Từ ngày'),
                    DatePicker::make('created_until')
                        ->label('Đến ngày'),
                ])
                ->query(function ($query, array $data) {
                    if ($data['created_from']) {
                        $query->whereDate('created_at', '>=', $data['created_from']);
                    }

                    if ($data['created_until']) {
                        $query->whereDate('created_at', '<=', $data['created_until']);
                    }

                    return $query;
                }),

            SelectFilter::make('show')
                ->label('Ẩn / Hiện bảng giá')
                ->options([
                    true => 'Hiện bảng giá',
                    false => 'Ẩn bảng giá'
                ]),
        ];
    }
}