<?php

namespace Modules\Form\App\Filament\Resources\FormNotificationResource\Tables\Actions;

use Filament\Actions\CreateAction;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;

class FormNotificationAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()->label('Xem chi tiết')->color('warning'),
                EditAction::make()->label('Cập nhật')->slideOver()->color('success'),
                DeleteAction::make()->label('Xóa')->color('danger'),
            ])
                ->size(ActionSize::Small)
                ->color('primary'),
        ];
    }
}