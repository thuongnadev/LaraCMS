<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class TaggableFilter
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