<?php

namespace Modules\PageMain\App\Filament\Resources\PageMainResource\Pages;

use Modules\PageMain\App\Filament\Resources\PageMainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPageMain extends EditRecord
{
    protected static string $resource = PageMainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->is_active) {
            $this->record->where('id', '!=', $this->record->id)->update(['is_active' => false]);
        }
    }
}