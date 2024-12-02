<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Tables\BulkActions;

use Filament\Tables;

class FooterBulkAction
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