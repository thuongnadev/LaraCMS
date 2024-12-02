<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Tables\Actions;

use Filament\Tables;
use Modules\Media\Traits\DeletesMedia;

class MediaBulkAction
{
    use DeletesMedia;

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                self::getBulkDeleteAction(),
            ]),
        ];
    }
}