<?php

namespace Modules\Post\App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Str;
use Modules\Post\App\Filament\Resources\PostResource;

class ViewPost extends ViewRecord
{
    protected static string $resource = PostResource::class;
    protected static string $view = 'post::pages.post-view';

    public function getBreadcrumbs(): array
    {
        /**
         * @var  $recordTitle
         * @props \Collection $title
         */
        $recordTitle = $this->record->title;

        /** @var  $shortenedTitle */
        $shortenedTitle = Str::limit($recordTitle, 50, '...');

        return [
            url('/admin/posts') => 'Bài Viết',
            $this->getResource()::getUrl('view', ['record' => $this->record]) => $shortenedTitle,
        ];
    }
}

