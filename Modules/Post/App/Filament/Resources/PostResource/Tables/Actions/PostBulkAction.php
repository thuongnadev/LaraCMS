<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Tables\Actions;

use Filament\Tables;
use Illuminate\Database\Eloquent\Collection;
use Modules\Post\Entities\Post;

class PostBulkAction
{
    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()
                    ->after(function (Collection $records) {
                        Post::deleteMediaForModels($records);
                    }),
            ]),
        ];
    }
}
