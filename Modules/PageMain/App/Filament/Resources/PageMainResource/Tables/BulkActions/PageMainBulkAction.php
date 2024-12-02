<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Tables\BulkActions;

use Filament\Tables;

class PageMainBulkAction
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