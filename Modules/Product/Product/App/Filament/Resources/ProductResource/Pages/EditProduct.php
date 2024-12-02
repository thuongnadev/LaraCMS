<?php

namespace Modules\Product\App\Filament\Resources\ProductResource\Pages;

use Modules\Product\App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $product = $this->record->load('skus.productVariantOptions');

        $skusWithoutVariants = $product->skus->filter(function ($sku) {
            return $sku->productVariantOptions->isEmpty();
        });

        $skusWithVariants = $product->skus->filter(function ($sku) {
            return $sku->productVariantOptions->isNotEmpty();
        });

        $data['skus'] = $skusWithoutVariants->map(function ($sku) {
            return [
                'sku' => $sku->sku,
                'price' => $sku->price,
                'stock' => $sku->stock,
            ];
        })->toArray();

        $data['product_variant_options'] = $skusWithVariants->map(function ($sku) {
            return $sku->productVariantOptions->map(function ($option) use ($sku) {
                return [
                    'variant_id' => $option->variant_id,
                    'name' => $option->name,
                    'value' => $option->value,
                    'sku' => $sku->sku,
                    'price' => $sku->price,
                    'stock' => $sku->stock,
                ];
            })->toArray();
        })->flatten(1)->toArray();

        return $data;
    }
}
