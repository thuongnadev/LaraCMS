<?php

namespace Modules\Media\Traits;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Filament\Forms\Set;

trait ImageUpload
{
    public static function makeImageUpload(
        string $name,
        string $label,
        string $directory = 'media',
        string $imagePreviewHeight = '75'
    ): FileUpload {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->imageEditor()
            ->imagePreviewHeight($imagePreviewHeight)
            ->openable()
            ->downloadable()
            ->deletable()
            ->acceptedFileTypes(['image/*'])
            ->maxSize(10240)
            ->storeFileNamesIn("{$name}_name")
            ->directory($directory)
            ->visibility('public')
            ->afterStateUpdated(function (Get $get, Set $set, $state) use ($name) {
                if ($state) {
                    $fileName = is_string($state) ? pathinfo($state, PATHINFO_BASENAME) : null;
                    $set("{$name}_name", $fileName);
                } else {
                    $set("{$name}_name", null);
                }
            });
    }
}
