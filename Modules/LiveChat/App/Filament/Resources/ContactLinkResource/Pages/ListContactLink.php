<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Pages;

use Modules\LiveChat\App\Filament\Resources\ContactLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactLink extends ListRecords
{
    protected static string $resource = ContactLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
