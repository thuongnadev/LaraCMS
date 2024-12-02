<?php

namespace App\Observers;

use App\Services\SitemapService;
use Modules\Menu\Entities\MenuItem;

class MenuItemObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function saved(MenuItem $menuItem)
    {
        $this->sitemapService->addOrUpdateUrl($menuItem->url, now(), 'weekly', 0.8);
    }

    public function deleted(MenuItem $menuItem)
    {
        $this->sitemapService->removeUrl($menuItem->url);
    }
}