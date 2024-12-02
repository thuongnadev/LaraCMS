<?php

namespace App\Observers;

use App\Services\SitemapService;
use Modules\Page\Entities\Page;

class PageObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function saved(Page $page)
    {
        $this->sitemapService->addOrUpdateUrl("pages/{$page->slug}", $page->updated_at);
    }

    public function deleted(Page $page)
    {
        $this->sitemapService->removeUrl("pages/{$page->slug}");
    }
}