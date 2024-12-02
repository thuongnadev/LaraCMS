<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Pages;

use Modules\Form\App\Filament\Resources\EmailConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailConfig extends EditRecord
{
    protected static string $resource = EmailConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}