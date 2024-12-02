<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Tables\BulkActions;

use Filament\Tables;

class EmailConfigBulkAction
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