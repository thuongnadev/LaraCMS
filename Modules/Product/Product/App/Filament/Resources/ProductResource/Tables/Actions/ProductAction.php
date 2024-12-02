<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Tables\Actions;

use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

class ProductAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()
                    ->label('Xem chi tiết')
                    ->modalHeading(fn($record) => "Xem chi tiết: {$record->name}")
                    ->modalDescription('Thông tin chi tiết của sản phẩm')
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                EditAction::make()
                    ->label('Cập nhật'),
                DeleteAction::make('Xóa')
            ])
        ];
    }
}
