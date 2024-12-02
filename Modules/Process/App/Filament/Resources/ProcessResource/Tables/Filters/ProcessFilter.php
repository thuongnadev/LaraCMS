<?php

namespace Modules\Process\App\Filament\Resources\ProcessResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class ProcessFilter
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