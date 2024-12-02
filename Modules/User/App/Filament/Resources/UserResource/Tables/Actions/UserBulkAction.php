<?php

namespace Modules\User\App\Filament\Resources\UserResource\Tables\Actions;

use Filament\Tables;

class UserBulkAction
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
