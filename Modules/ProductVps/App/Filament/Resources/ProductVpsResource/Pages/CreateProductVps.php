<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Pages;

use Modules\ProductVps\App\Filament\Resources\ProductVpsResource;
use Filament\Resources\Pages\CreateRecord;
use Modules\Media\Traits\HandleFileUploadMedia;

class CreateProductVps extends CreateRecord
{
    use HandleFileUploadMedia;
    protected static string $resource = ProductVpsResource::class;

    protected function afterCreate(): void
    {
        $categories = $this->data['categories'] ?? [];
        $this->record->categories()->sync($categories);
        $this->UploadMedia($this->data, $this->record);
    }
}
