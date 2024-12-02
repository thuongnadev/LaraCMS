<?php

namespace Modules\Comment\App\Filament\Resources\CommentProductResource\Tables\Filters;

use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
class CommentProductFilter
{
    public static function fillter()
    {
        return [
            SelectFilter::make('like')
                ->label('Thích')
                ->options([
                    true => 'Bình luận có thích',
                    false => 'Bình luận không có thích',
                ])->columnSpan(4),
            SelectFilter::make('dislike')
                ->label('Không Thích')
                ->options([
                    true => 'Bình luận có  không thích',
                    false => 'Bình luận không có  không thích',
                ])->columnSpan(4),
            SelectFilter::make('pin')
                ->label('Ghim')
                ->options([
                    true => 'Bình luận có gim',
                    false => 'Bình luận không có gim',
                ])->columnSpan(4),
            SelectFilter::make('flag')
                ->label('Báo cáo')
                ->options([
                    true => 'Bình luận có báo cáo',
                    false => 'Bình luận không có báo cáo',
                ])->columnSpan(12),
            Filter::make('created_at')
                ->label('Ngày tạo')
                ->form([
                    DatePicker::make('Từ')->columnSpan(12),
                    DatePicker::make('Đến')->columnSpan(12),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['Từ'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['Đến'],
                            fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })->columns(12)
        ];
    }
}
