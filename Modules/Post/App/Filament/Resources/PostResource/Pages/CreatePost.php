<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Pages;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Post\App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;
use Modules\Tag\Entities\Tag;
use Modules\Category\Entities\Category;
use Modules\Media\Traits\HandleFileUploadMedia;
use Modules\Post\Traits\SchedulePostStatusUpdate;

/**
 * @property \Illuminate\Support\Collection $tagsToAttach
 * @property \Illuminate\Support\Collection $categoriesToAttach
 */
class CreatePost extends CreateRecord
{
    use HandleFileUploadMedia,
        SchedulePostStatusUpdate;

    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::id();

        if (!empty($data['tags'])) {
            $tagNames = is_string($data['tags']) ? array_map('trim', explode(',', $data['tags'])) : $data['tags'];
            $this->tagsToAttach = $this->processEntities($tagNames, Tag::class);
        } else {
            $this->tagsToAttach = collect();
        }
        unset($data['tags']);

        $categoryIds = $data['categories'] ?? [];
        $this->categoriesToAttach = Category::whereIn('id', $categoryIds)->get();
        unset($data['categories']);

        return $data;
    }

    protected function processEntities(array $names, string $class)
    {
        return collect($names)->map(function ($name) use ($class) {
            $slug = Str::slug($name);
            $entity = $class::where('slug', $slug)->first();
            if (!$entity) {
                $entity = $class::create([
                    'name' => $name,
                    'slug' => $this->generateUniqueSlug($slug, $class),
                ]);
            }
            return $entity;
        });
    }

    protected function generateUniqueSlug($slug, $class)
    {
        $originalSlug = $slug;
        $counter = 1;

        while ($class::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function afterCreate(): void
    {
        $this->UploadMedia($this->data, $this->record);
        $this->record->tags()->sync($this->tagsToAttach->pluck('id')->toArray());
        $this->record->categories()->sync($this->categoriesToAttach->pluck('id')->toArray());
        $this->schedulePostStatusUpdate($this->record);
    }
}
