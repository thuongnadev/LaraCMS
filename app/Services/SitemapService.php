<?php

namespace App\Services;

use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use \SimpleXMLElement;
use \DateTime;
use \Exception;

class SitemapService
{
    public function addOrUpdateUrl($url, $lastModificationDate = null, $changeFrequency = Url::CHANGE_FREQUENCY_DAILY, $priority = 0.8)
    {
        $sitemap = $this->getSitemap();

        $url = url($url);
        $tags = $sitemap->getTags();

        $existingUrl = null;
        foreach ($tags as $tag) {
            if ($tag->url === $url) {
                $existingUrl = $tag;
                break;
            }
        }

        if ($existingUrl) {
            $existingUrl->setLastModificationDate($lastModificationDate)
                ->setChangeFrequency($changeFrequency)
                ->setPriority($priority);
        } else {
            $sitemap->add(Url::create($url)
                ->setLastModificationDate($lastModificationDate)
                ->setChangeFrequency($changeFrequency)
                ->setPriority($priority));
        }

        $this->saveSitemap($sitemap);
    }

    public function removeUrl($url)
    {
        $sitemap = $this->getSitemap();
        $url = url($url);

        $tags = $sitemap->getTags();
        $filteredTags = array_filter($tags, function ($tag) use ($url) {
            return $tag->url !== $url;
        });

        // Tạo sitemap mới với các tags đã lọc
        $newSitemap = Sitemap::create();
        foreach ($filteredTags as $tag) {
            $newSitemap->add($tag);
        }

        $this->saveSitemap($newSitemap);
    }

    private function getSitemap()
    {
        $path = public_path('sitemap.xml');
        if (file_exists($path)) {
            $sitemap = Sitemap::create();
            $xmlContent = file_get_contents($path);

            if ($xmlContent === false) {
                return Sitemap::create();
            }

            try {
                $xml = new SimpleXMLElement($xmlContent);
            } catch (Exception $e) {
                return Sitemap::create();
            }

            foreach ($xml->url as $urlElement) {
                $url = (string)$urlElement->loc;
                $lastmod = isset($urlElement->lastmod) ? (string)$urlElement->lastmod : null;
                $changefreq = isset($urlElement->changefreq) ? (string)$urlElement->changefreq : null;
                $priority = isset($urlElement->priority) ? (float)$urlElement->priority : null;

                $sitemapUrl = Url::create($url);

                if ($lastmod) {
                    try {
                        $lastmodDate = new DateTime($lastmod);
                        $sitemapUrl->setLastModificationDate($lastmodDate);
                    } catch (Exception $e) {
                        // Xử lý lỗi nếu không thể chuyển đổi ngày
                    }
                }
                if ($changefreq) {
                    $sitemapUrl->setChangeFrequency($changefreq);
                }
                if ($priority !== null) {
                    $sitemapUrl->setPriority($priority);
                }

                $sitemap->add($sitemapUrl);
            }

            return $sitemap;
        }

        return Sitemap::create();
    }

    private function saveSitemap(Sitemap $sitemap)
    {
        $sitemap->writeToFile(public_path('sitemap.xml'));
    }
}
