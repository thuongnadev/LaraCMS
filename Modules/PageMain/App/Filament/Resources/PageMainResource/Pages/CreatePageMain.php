<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Pages;

use Modules\PageMain\App\Filament\Resources\PageMainResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageMain extends CreateRecord
{
    protected static string $resource = PageMainResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->is_active) {
            $this->record->where('id', '!=', $this->record->id)->update(['is_active' => false]);
        }
    }
}