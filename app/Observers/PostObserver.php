<?php

namespace App\Observers;

use App\Services\SitemapService;
use Modules\Post\Entities\Post;

class PostObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function saved(Post $post)
    {
        $this->sitemapService->addOrUpdateUrl("bai-viet/{$post->slug}", $post->updated_at, 'weekly', 0.7);
    }

    public function deleted(Post $post)
    {
        $this->sitemapService->removeUrl("bai-viet/{$post->slug}");
    }
}