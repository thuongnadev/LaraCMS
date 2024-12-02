<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Pages;

use Modules\Footer\App\Filament\Resources\FooterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFooter extends CreateRecord
{
    protected static string $resource = FooterResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->status) {
            $this->record->where('id', '!=', $this->record->id)->update(['status' => false]);
        }
    }
}
