<?php

namespace Modules\Media\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Modules\Media\Entities\Media;
use Modules\Media\Entities\MediaHasModel;

trait HandleFileUploadMedia
{
    protected function UploadMedia(array $data, ?Model $model = null)
    {
        $filteredData = $this->filterMediaData($data);

        $dataFile = $this->extractFilePathsAndNames($filteredData);

        $filePaths = $dataFile['filePaths'];
        $fileNames = $dataFile['fileNames'];


        $mediaIds = [];
        foreach ($filePaths as $index => $filePath) {

            $newFileName = $fileNames[$index] ?? pathinfo($filePath, PATHINFO_BASENAME);

            if (!is_string($filePath)) {
                continue;
            }

            $media = $this->createNewMedia($newFileName, $filePath, $data, $model);

            $mediaIds[] = $media->id;

            if ($model) {
                $fieldName = $this->getFieldNameFromFilePath($filePath, $data);
                $this->createMediaHasModel($media, $model, $fieldName);
            }
        }

        return $mediaIds;
    }


    protected function UpdateMedia(array $data, ?Model $model = null)
    {
        $filteredData = $this->filterMediaData($data);
        $dataFile = $this->extractFilePathsAndNames($filteredData);

        $filePaths = $dataFile['filePaths'];
        $fileNames = $dataFile['fileNames'];

        $updatedMediaIds = [];
        $changedFields = [];

        foreach ($filePaths as $index => $filePath) {
            $newFileName = $fileNames[$index] ?? pathinfo($filePath, PATHINFO_BASENAME);
            if (!is_string($filePath)) {
                continue;
            }

            $fieldName = $this->getFieldNameFromFilePath($filePath, $data);
            $allowMultiple = $this->isMultipleMediaField($fieldName, $data);

            if ($model instanceof Media) {
                $this->updateExistingMedia($model, $newFileName, $filePath, $data, $model);
                $updatedMediaIds[] = $model->id;
            } else {
                $fieldName = $this->getFieldNameFromFilePath($filePath, $data);
                $existingMedia = $this->findExistingMedia($filePath, $model, $fieldName);

                if ($existingMedia) {
                    $this->updateExistingMedia($existingMedia, $newFileName, $filePath, $data, $model);
                    $updatedMediaIds[] = $existingMedia->id;
                    $changedFields[] = $fieldName;
                } else {
                    $newMedia = $this->createNewMedia($newFileName, $filePath, $data, $model);
                    $updatedMediaIds[] = $newMedia->id;
                    $changedFields[] = $fieldName;
                }

                if ($model) {
                    $this->updateOrCreateMediaHasModel($existingMedia ?? $newMedia, $model, $fieldName, $allowMultiple);
                }
            }
        }

        if ($model instanceof Media) {
            $this->deleteUnusedFiles($model, $filePaths);
        } elseif ($model && !empty($changedFields)) {
            foreach (array_unique($changedFields) as $fieldName) {
                $allowMultiple = $this->isMultipleMediaField($fieldName, $data);
                if (!$allowMultiple) {
                    $this->removeUnusedMediaForField($model, $fieldName, $updatedMediaIds);
                }
            }
        }

        if ($model && !($model instanceof Media)) {
            $this->removeUnusedMediaLinks($model, $updatedMediaIds);
        }

        return $updatedMediaIds;
    }

    protected function filterMediaData(array $data)
    {
        $filteredData = [];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'tiff', 'svg', 'ico', 'heic', 'heif', 'psd'];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $filteredValue = array_filter($value, function ($item) use ($imageExtensions) {
                    if (is_string($item)) {
                        $extension = pathinfo($item, PATHINFO_EXTENSION);
                        return in_array(strtolower($extension), $imageExtensions);
                    }
                    return false;
                });

                if (!empty($filteredValue)) {
                    $filteredData[$key] = $filteredValue;
                }
            } elseif (is_string($value)) {
                $extension = pathinfo($value, PATHINFO_EXTENSION);
                if (in_array(strtolower($extension), $imageExtensions)) {
                    $filteredData[$key] = $value;
                }
            }
        }

        return $filteredData;
    }

    protected function getFieldNameFromFilePath($filePath, $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && in_array($filePath, $value)) {
                return $key;
            } elseif ($value === $filePath) {
                return $key;
            }
        }
        return null;
    }

    protected function extractFilePathsAndNames(array $data): array
    {
        $filePaths = [];
        $fileNames = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (strpos($key, '_name') !== false) {
                    foreach ($value as $filePath => $fileName) {
                        $fileNames[] = $fileName;
                    }
                } else {
                    foreach ($value as $uuid => $filePath) {
                        $filePaths[] = $filePath;
                    }
                }
            } else {
                if (strpos($key, '_name') !== false) {
                    $fileNames[] = $value;
                } else {
                    $filePaths[] = $value;
                }
            }
        }

        return [
            'filePaths' => $filePaths,
            'fileNames' => $fileNames,
        ];
    }

    protected function findExistingMedia($filePath, ?Model $model = null, $fieldName = null)
    {
        $query = Media::where('file_path', $filePath);

        if ($model) {
            $existingMediaIds = $model->media()
                ->when($fieldName, function ($query) use ($fieldName) {
                    return $query->where('model_name', $fieldName);
                })
                ->pluck('media.id')
                ->toArray();
            $query->whereIn('id', $existingMediaIds);
        }

        return $query->first();
    }

    protected function updateExistingMedia($media, $newFileName, $filePath, $data, ?Model $model = null)
    {
        if ($media->file_path !== $filePath) {
            $this->deleteOldFile($media);
        }

        $updateData = [
            'file_name' => $newFileName,
            'file_path' => $filePath,
            'file_type' => pathinfo($newFileName, PATHINFO_EXTENSION),
            'mime_type' => Storage::disk('public')->mimeType($filePath),
            'file_size' => Storage::disk('public')->size($filePath),
        ];

        if ($model instanceof Media) {
            $updateData['name'] = $data['name'] ?? $media->name;
            $updateData['alt_text'] = $data['alt_text'] ?? $media->alt_text;
            $updateData['description'] = $data['description'] ?? $media->description;
        }

        $media->update($updateData);
    }

    protected function createNewMedia($newFileName, $filePath, $data, ?Model $model = null)
    {
        $mediaData = [
            'name' => pathinfo($newFileName, PATHINFO_FILENAME),
            'file_name' => $newFileName,
            'file_path' => $filePath,
            'file_type' => pathinfo($newFileName, PATHINFO_EXTENSION),
            'mime_type' => Storage::disk('public')->mimeType($filePath),
            'file_size' => Storage::disk('public')->size($filePath),
            'uploaded_by' => auth()->id(),
        ];

        if ($model instanceof Media) {
            $mediaData['alt_text'] = $data['alt_text'] ?? null;
            $mediaData['description'] = $data['description'] ?? null;
        }

        return Media::create($mediaData);
    }

    protected function updateOrCreateMediaHasModel($media, $model, $fieldName, $allowMultiple = false)
    {
        if (!$allowMultiple) {
            MediaHasModel::updateOrCreate(
                [
                    'model_id' => $model->id,
                    'model_type' => get_class($model),
                    'model_name' => $fieldName,
                ],
                [
                    'media_id' => $media->id,
                ]
            );
        } else {
            MediaHasModel::firstOrCreate(
                [
                    'media_id' => $media->id,
                    'model_id' => $model->id,
                    'model_type' => get_class($model),
                    'model_name' => $fieldName,
                ]
            );
        }
    }

    protected function createMediaHasModel($media, $model, $fieldName)
    {
        MediaHasModel::create([
            'media_id' => $media->id,
            'model_id' => $model->id,
            'model_type' => get_class($model),
            'model_name' => $fieldName,
        ]);
    }

    protected function removeUnusedMediaLinks(Model $model, array $mediaIds)
    {
        MediaHasModel::where('model_id', $model->id)
            ->where('model_type', get_class($model))
            ->whereNotIn('media_id', $mediaIds)
            ->delete();
    }

    protected function deleteUnusedFiles($media, $newFilePaths)
    {
        $oldFilePaths = explode(',', $media->file_path);
        $unusedFilePaths = array_diff($oldFilePaths, $newFilePaths);

        foreach ($unusedFilePaths as $unusedFilePath) {
            if (Storage::disk('public')->exists($unusedFilePath)) {
                Storage::disk('public')->delete($unusedFilePath);
            }
        }
    }

    protected function deleteOldFile($media)
    {
        if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }
    }

    protected function isMultipleMediaField($fieldName, $data)
    {
        if (isset($data[$fieldName]) && is_array($data[$fieldName]) && count($data[$fieldName]) > 1) {
            return true;
        }

        if (isset($data[$fieldName . '_multiple']) && $data[$fieldName . '_multiple'] === true) {
            return true;
        }
        return false;
    }

    protected function removeUnusedMediaForField($model, $fieldName, $updatedMediaIds)
    {
        $unusedMedia = $model->media()
            ->where('model_name', $fieldName)
            ->whereNotIn('media.id', $updatedMediaIds)
            ->get();

        foreach ($unusedMedia as $media) {
            $model->media()->detach($media->id);
            if ($media->mediaHasModels()->count() === 0) {
                $this->deleteOldFile($media);
                $media->delete();
            }
        }
    }
}