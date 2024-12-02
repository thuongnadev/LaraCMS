<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Pages;

use Modules\Product\App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}