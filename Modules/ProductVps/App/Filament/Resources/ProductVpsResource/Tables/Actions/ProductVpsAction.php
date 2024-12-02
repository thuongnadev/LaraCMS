<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Tables\Actions;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Modules\ProductVps\Entities\ProductVps;

class ProductVpsAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()->label('Xem chi tiết'),
                EditAction::make()->label('Cập nhật'),
                DeleteAction::make('Xóa')
                    ->after(function (ProductVps $record) {
                        ProductVps::deleteMediaForModel($record);
                    }),
            ])
        ];
    }
}
