<?php

namespace Modules\Header\App\Filament\Resources\HeaderResource\Pages;

use Modules\Header\App\Filament\Resources\HeaderResource;
use Filament\Resources\Pages\CreateRecord;
use Modules\Header\Entities\Header;

class CreateHeader extends CreateRecord
{
    protected static string $resource = HeaderResource::class;

    protected function afterCreate(): void
    {
        if ($this->record->status) {
            $this->record->where('id', '!=', $this->record->id)->update(['status' => false]);
        }
    }
}
