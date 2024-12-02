<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class FooterFilter
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