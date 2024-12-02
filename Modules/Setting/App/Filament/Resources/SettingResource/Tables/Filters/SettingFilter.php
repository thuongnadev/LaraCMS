<?php

namespace Modules\Setting\App\Filament\Resources\SettingResource\Tables\Filters;

use Filament\Tables\Filters\SelectFilter;

class SettingFilter
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