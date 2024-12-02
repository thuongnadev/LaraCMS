<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class PageMainFilter
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