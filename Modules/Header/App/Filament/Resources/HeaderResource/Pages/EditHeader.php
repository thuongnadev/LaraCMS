<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Pages;

use Modules\Header\App\Filament\Resources\HeaderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Header\Entities\Header;

class EditHeader extends EditRecord
{
    protected static string $resource = HeaderResource::class;

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
