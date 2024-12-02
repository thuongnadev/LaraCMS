<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Pages;

use Modules\Header\App\Filament\Resources\HeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeader extends ListRecords
{
    protected static string $resource = HeaderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
