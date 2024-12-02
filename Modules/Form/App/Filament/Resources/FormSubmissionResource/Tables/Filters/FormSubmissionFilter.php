<?php

namespace Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class FormSubmissionFilter
{
    public static function filter(): array
    {
        return [
            SelectFilter::make('is_viewed')
                ->options([
                    '1' => 'Đã xem',
                    '0' => 'Chưa xem',
                ])
                ->label('Trạng thái xem'),
        ];
    }
}