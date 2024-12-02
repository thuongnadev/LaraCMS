<?php

namespace Modules\LiveChat\App\Filament\Resources\ContactLinkResource\Pages;

use Modules\LiveChat\App\Filament\Resources\ContactLinkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactLink extends EditRecord
{
    protected static string $resource = ContactLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}