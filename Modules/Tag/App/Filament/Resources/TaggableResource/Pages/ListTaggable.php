<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Pages;

use Modules\Tag\App\Filament\Resources\TaggableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaggable extends ListRecords
{
    protected static string $resource = TaggableResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
