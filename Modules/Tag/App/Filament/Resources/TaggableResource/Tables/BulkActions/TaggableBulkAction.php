<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Tables\BulkActions;

use Filament\Tables;

class TaggableBulkAction
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