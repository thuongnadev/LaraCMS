<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\BulkActions;

use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Modules\ProductVps\Entities\ProductVps;

class ProductVpsBulkAction
{
    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function (Collection $records) {
                        ProductVps::deleteMediaForModels($records);
                    }),
            ]),
        ];
    }
}
