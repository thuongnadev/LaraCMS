<?php

namespace Modules\ProductVps\App\Filament\Resources\ProductVpsResource\Pages;

use Modules\ProductVps\App\Filament\Resources\ProductVpsResource;
use Filament\Resources\Pages\EditRecord;
use Modules\Media\Traits\HandleFileUploadMedia;
use Modules\Media\Traits\ImageDisplay;
use Modules\ProductVps\Entities\ProductVps;
use Filament\Actions;

class EditProductVps extends EditRecord
{
    use HandleFileUploadMedia,
        ImageDisplay;

    protected static string $resource = ProductVpsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function (ProductVps $record) {
                    ProductVps::deleteMediaForModel($record);
                }),

        ];
    }

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        $this->syncCategories($this->data);

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    protected function syncCategories($data) {
        $categoriesData = $data['categories'] ?? [];
        $this->record->categories()->sync($categoriesData);
    }

    protected function afterFill(): void
    {
        $data = $this->record->toArray();

        $data['categories'] = $this->record->categories->pluck('id')->toArray();

        $mainImageData = $this->prepareSingleImageData($this->record, 'product_image', 'productImage');
        $data = array_merge($data, $mainImageData);

        $this->form->fill($data);
    }
    protected function afterSave(): void
    {
        $this->UpdateMedia($this->data, $this->record);
        $categories = $this->data['categories'] ?? [];
        $this->record->categories()->sync($categories);
    }
}
