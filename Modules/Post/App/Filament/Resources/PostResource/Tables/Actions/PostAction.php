<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Tables\Actions;

use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Modules\Post\Entities\Post;

class PostAction
{
    public static function action()
    {
        return [
            ActionGroup::make([
                ViewAction::make()
                    ->label('Xem chi tiết'),
                EditAction::make()
                    ->label('Cập nhật'),
                DeleteAction::make('Xóa')
                ->after(function (Post $record) {
                    Post::deleteMediaForModel($record);
                }),

            ])
        ];
    }
}
