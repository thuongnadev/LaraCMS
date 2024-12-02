<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Pages;

use Modules\PageMain\App\Filament\Resources\PageMainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPageMain extends ListRecords
{
    protected static string $resource = PageMainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
