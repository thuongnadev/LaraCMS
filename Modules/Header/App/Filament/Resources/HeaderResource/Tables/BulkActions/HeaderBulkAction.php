<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Tables\BulkActions;

use Filament\Tables;

class HeaderBulkAction
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