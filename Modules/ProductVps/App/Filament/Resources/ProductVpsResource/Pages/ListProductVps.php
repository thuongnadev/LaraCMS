<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Pages;

use Modules\ProductVps\App\Filament\Resources\ProductVpsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductVps extends ListRecords
{
    protected static string $resource = ProductVpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
