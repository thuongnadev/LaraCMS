<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class ContactLinkFilter
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