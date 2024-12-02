<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Tables\Actions;

use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

class HeaderAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()->label('Xem chi tiết'),
                EditAction::make()->label('Cập nhật'),
                DeleteAction::make('Xóa')
            ])
                ->size(ActionSize::Small)
                ->color('primary'),
        ];
    }
}