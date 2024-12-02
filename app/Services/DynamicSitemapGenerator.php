<?php

namespace App\Services;

use Modules\Menu\Entities\MenuItem;
use Modules\Page\Entities\Page;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Crawler\Crawler;
use Spatie\Sitemap\SitemapGenerator;
use Illuminate\Support\Str;
use Modules\Post\Entities\Post;
use Modules\ProductVps\Entities\ProductVps;

class DynamicSitemapGenerator
{
    protected $sitemap;
    protected $baseUrl;
    protected $excludedUrls;

    public function __construct()
    {
        $this->baseUrl = config('app.url');
        $this->excludedUrls = ['/login', '/register', '/admin/*'];
        $this->sitemap = Sitemap::create();
    }

    public function generate()
    {
        $this->addDynamicPages();
        $this->addMenuItems();
        $this->addPosts();
        $this->addProducts();
        $this->crawlRemainingPages();

        return $this->sitemap;
    }

    protected function addDynamicPages()
    {
        Page::chunk(100, function ($pages) {
            foreach ($pages as $page) {
                $this->sitemap->add(
                    Url::create($this->getFullUrl($page->slug))
                        ->setLastModificationDate($page->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }
        });
    }

    protected function addMenuItems()
    {
        MenuItem::chunk(100, function ($menuItems) {
            foreach ($menuItems as $item) {
                if (!$this->isExcluded($item->url)) {
                    $this->sitemap->add(
                        Url::create($this->getFullUrl($item->url))
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.9)
                    );
                }
            }
        });
    }

    protected function addPosts()
    {
        Post::chunk(100, function ($posts) {
            foreach ($posts as $post) {
                $this->sitemap->add(
                    Url::create($this->getFullUrl("bai-viet/{$post->slug}"))
                        ->setLastModificationDate($post->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.7)
                );
            }
        });
    }

    protected function addProducts()
    {
        ProductVps::chunk(100, function ($products) {
            foreach ($products as $product) {
                $this->sitemap->add(
                    Url::create($this->getFullUrl("san-pham/{$product->slug}"))
                        ->setLastModificationDate($product->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setPriority(0.8)
                );
            }
        });
    }

    protected function getFullUrl($path)
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        return rtrim($this->baseUrl, '/') . '/' . ltrim($path, '/');
    }

    protected function crawlRemainingPages()
    {
        SitemapGenerator::create($this->baseUrl)
            ->configureCrawler(function (Crawler $crawler) {
                $crawler->setConcurrency(5)
                    ->setMaximumDepth(5)
                    ->ignoreRobots();
            })
            ->getSitemap()
            ->writeToFile(public_path('sitemap.xml'));
    }

    protected function isExcluded($url)
    {
        foreach ($this->excludedUrls as $pattern) {
            if (Str::is($pattern, $url)) {
                return true;
            }
        }
        return false;
    }
}
