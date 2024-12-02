<?php

namespace Modules\Category\App\Filament\Resources\CategoryResource\Tables\BulkActions;

use Filament\Tables;

class CategoryBulkAction
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