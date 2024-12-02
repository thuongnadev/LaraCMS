<?php

namespace Modules\Footer\App\Filament\Resources\FooterResource\Pages;

use Modules\Footer\App\Filament\Resources\FooterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFooter extends EditRecord
{
    protected static string $resource = FooterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record->status) {
            $this->record->where('id', '!=', $this->record->id)->update(['status' => false]);
        }
    }
}
