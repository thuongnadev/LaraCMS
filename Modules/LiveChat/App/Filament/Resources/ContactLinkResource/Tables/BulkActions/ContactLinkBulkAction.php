<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Tables\BulkActions;

use Filament\Tables;

class ContactLinkBulkAction
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