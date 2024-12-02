<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Pages;

use Modules\Form\App\Filament\Resources\EmailConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmailConfig extends ListRecords
{
    protected static string $resource = EmailConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
