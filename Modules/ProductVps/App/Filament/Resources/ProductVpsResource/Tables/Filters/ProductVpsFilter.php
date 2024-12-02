<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Modules\Category\Entities\Category;

class ProductVpsFilter
{
    public static function filter(): array
    {
        return [
            SelectFilter::make('categories')
                ->label('Danh mục')
                ->preload()
                ->options(Category::pluck('name', 'id')->toArray())
                ->query(function ($query, $state) {
                    if ($state) {
                        if($state['value'] == null) { 
                            $query->whereHas('categories', function ($query) use ($state) {
                                $query->whereIn('categories.id', Category::all()->pluck('id')->toArray());
                            });
                        } else {
                            $query->whereHas('categories', function ($query) use ($state) {
                                $query->whereIn('categories.id', $state);
                            });
                        }
                    }
                }),

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
        ];
    }
}
