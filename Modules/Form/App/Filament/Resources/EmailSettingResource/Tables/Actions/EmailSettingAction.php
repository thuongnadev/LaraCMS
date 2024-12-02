<?php

namespace Modules\Form\App\Filament\Resources\EmailSettingResource\Tables\Actions;

use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

class EmailSettingAction
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