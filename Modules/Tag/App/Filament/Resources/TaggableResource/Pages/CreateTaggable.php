<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Pages;

use Modules\Tag\App\Filament\Resources\TaggableResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaggable extends CreateRecord
{
    protected static string $resource = TaggableResource::class;
}