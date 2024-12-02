<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Pages;

use Modules\Footer\App\Filament\Resources\FooterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFooter extends ListRecords
{
    protected static string $resource = FooterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
