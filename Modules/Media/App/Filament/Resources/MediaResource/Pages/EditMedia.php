<?php

namespace Modules\Media\App\Filament\Resources\MediaResource\Pages;

use Filament\Actions\DeleteAction;
use Modules\Media\App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Media\Traits\HandleFileUploadMedia;

class EditMedia extends EditRecord
{
    use HandleFileUploadMedia;

    protected static string $resource = MediaResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make()
                ->using(function ($record) {
                    MediaResource::deleteMediaRecord($record);
                }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $this->UpdateMedia($data, $record);
            return $record->fresh();
        });
    }

}
