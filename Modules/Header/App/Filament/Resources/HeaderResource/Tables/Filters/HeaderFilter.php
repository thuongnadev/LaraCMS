<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class HeaderFilter
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