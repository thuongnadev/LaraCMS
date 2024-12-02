<?php

namespace Modules\Setting\App\Filament\Resources\SettingResource\Tables\BulkActions;

use Filament\Tables;

class SettingBulkAction
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