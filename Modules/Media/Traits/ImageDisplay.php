<?php

namespace Modules\Media\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\ImageColumn;
use Closure;


trait ImageDisplay
{
    protected function prepareSingleImageData(Model $record, string $fieldName, string $imageRelation): array
    {
        $data = [];
        $image = $record->$imageRelation()->first();
        if ($image) {
            $data[$fieldName] = $image->file_path;
            $data["{$fieldName}_name"] = $image->file_name;
        }
        return $data;
    }

    protected function prepareMultipleImagesData(Model $record, string $fieldName, string $imagesRelation): array
    {
        $data = [];
        $images = $record->$imagesRelation;
        if ($images instanceof Collection && $images->isNotEmpty()) {
            $data[$fieldName] = $images->pluck('file_path')->toArray();
            $data["{$fieldName}_name"] = $images->pluck('file_name')->toArray();
        }
        return $data;
    }

    public static function getImageColumn(
        string $modelName = 'main_image',
        string $label = 'Hình ảnh',
        int $size = 50,
        bool $multiple = false
    ): Closure {
        return function () use ($modelName, $label, $size, $multiple) {
            $column = ImageColumn::make($modelName)
                ->label($label)
                ->size($size)
                ->defaultImageUrl(asset('/img-default.jpg'));

            if ($multiple) {
                $column->getStateUsing(function (Model $record) use ($modelName) {
                    return static::getImagePaths($record, $modelName, true);
                });
            } else {
                $column->getStateUsing(function (Model $record) use ($modelName) {
                    return static::getImagePath($record, $modelName);
                });
            }

            return $column;
        };
    }

    protected static function getImagePaths(Model $record, string $modelName, bool $multiple): array
    {
        $mediaHasModels = static::getMediaHasModels($record, $modelName);
        return $mediaHasModels->pluck('media.file_path')->filter()->toArray();
    }

    protected static function getImagePath(Model $record, string $modelName): ?string
    {
        $mediaHasModel = static::getMediaHasModels($record, $modelName)->first();
        return $mediaHasModel?->media?->file_path;
    }

    protected static function getMediaHasModels(Model $record, string $modelName)
    {
        return $record->mediaHasModel()
            ->where('model_name', $modelName)
            ->with('media')
            ->get();
    }
}
