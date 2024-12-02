<?php

namespace Modules\SettingCompany\App\Filament\Resources\SettingCompanyResource\Tables\BulkActions;

use Filament\Tables;

class SettingCompanyBulkAction
{
    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
            ]),
        ];
    }
}