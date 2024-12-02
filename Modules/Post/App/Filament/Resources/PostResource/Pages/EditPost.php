<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Pages;

use Illuminate\Support\Str;
use Modules\Post\App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Media\Traits\HandleFileUploadMedia;
use Modules\Media\Traits\ImageDisplay;
use Modules\Post\Entities\Post;
use Modules\Post\Traits\SchedulePostStatusUpdate;
use Modules\Tag\Entities\Tag;

class EditPost extends EditRecord
{
    use ImageDisplay,
        HandleFileUploadMedia,
        SchedulePostStatusUpdate;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->after(function (Post $record) {
                Post::deleteMediaForModel($record);
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

        $data['tags'] = $this->record->tags->pluck('name')->toArray();
        $data['categories'] = $this->record->categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        })->toArray();
        $mainImageData = $this->prepareSingleImageData($this->record, 'post_image', 'postImage');
        $data = array_merge($data, $mainImageData);


        $this->form->fill($data);
    }

    protected function afterSave(): void
    {
        $this->UpdateMedia($this->data, $this->record);
        
        $tagNames = $this->data['tags'] ?? [];
        if (is_array($tagNames)) {
            $tags = collect($tagNames)->filter(function ($tagName) {
                return is_string($tagName) && !empty(trim($tagName));
            })->map(function ($tagName) {
                return $this->createUniqueTag($tagName);
            });

            $this->record->tags()->sync($tags->pluck('id')->toArray());
            $this->redirect($this->getResource()::getUrl('index'));
        } else {
            $this->record->tags()->detach();
        }
        
        $this->schedulePostStatusUpdate($this->record);

        $this->redirect($this->getResource()::getUrl('index'));
    }

    protected function createUniqueTag(string $tagName): Tag
    {
        $trimmedTagName = trim($tagName);
        $slug = Str::slug($trimmedTagName);

        $existingTag = Tag::where('slug', $slug)->first();

        if ($existingTag) {
            $count = 2;
            $originalSlug = $slug;
            while (Tag::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
        }

        return Tag::UpdateOrCreate(
            ['name' => $trimmedTagName],
            ['slug' => $slug]
        );
    }
}