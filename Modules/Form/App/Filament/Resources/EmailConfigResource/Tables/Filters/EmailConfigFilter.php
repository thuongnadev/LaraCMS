<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class EmailConfigFilter
{
    public static function filter(): array
    {
        return [
            SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                ])
                ->label('Status'),
            // Add more filters here
        ];
    }
}