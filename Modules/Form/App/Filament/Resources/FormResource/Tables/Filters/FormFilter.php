<?php

namespace Modules\Form\App\Filament\Resources\FormResource\Tables\Filters;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class FormFilter
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

            SelectFilter::make('has_required_fields')
                ->options([
                    '1' => 'Trường bắt buộc',
                    '0' => 'Trường không bắt buộc',
                ])
                ->label('Trạng thái')
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['value'] !== null,
                        function (Builder $query) use ($data) {
                            return $query->whereHas('formFields', function (Builder $subQuery) use ($data) {
                                $subQuery->where('is_required', $data['value']);
                            }, '>', 0);
                        }
                    );
                }),
        ];
    }
}
