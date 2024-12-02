<?php

namespace App\Observers;

use App\Services\SitemapService;
use Modules\ProductVps\Entities\ProductVps;

class ProductVpsObserver
{
    protected $sitemapService;

    public function __construct(SitemapService $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    public function saved(ProductVps $product)
    {
        $this->sitemapService->addOrUpdateUrl("san-pham/{$product->slug}", $product->updated_at, 'daily', 0.8);
    }

    public function deleted(ProductVps $product)
    {
        $this->sitemapService->removeUrl("san-pham/{$product->slug}");
    }
}