<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\App\Filament\Resources\MediaResource;
use Illuminate\Support\Facades\DB;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\HandleFileUploadMedia;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    use HandleFileUploadMedia;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $mediaIds = $this->UploadMedia($data, $this->record);
            
            return Media::findOrFail($mediaIds[0]);
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
