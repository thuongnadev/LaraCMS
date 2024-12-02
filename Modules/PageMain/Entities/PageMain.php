<?php

namespace Modules\PageMain\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PageMain extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_locale',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_site',
        'twitter_creator',
        'canonical_url',
        'robots',
        'author',
        'published_time',
        'modified_time',
        'section',
        'tag',
        'sitemap_priority',
        'sitemap_frequency',
        'google_analytics_id',
        'schema_type',
        'content',
        'is_active',
    ];

    protected $casts = [
        'published_time' => 'datetime',
        'modified_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'title' => 'Trang chủ mặc định',
        'meta_description' => 'Mô tả mặc định cho trang web',
        'meta_keywords' => 'từ khóa mặc định, seo, website',
        'robots' => 'index, follow',
        'og_type' => 'website',
        'og_locale' => 'vi_VN',
        'twitter_card' => 'summary_large_image',
        'sitemap_priority' => '0.5',
        'sitemap_frequency' => 'weekly',
        'schema_type' => 'WebPage',
        'is_active' => true,
    ];

    public function getTitle()
    {
        return $this->title ?? $this->attributes['title'];
    }

    public function getMetaDescription()
    {
        return $this->meta_description ?? $this->attributes['meta_description'];
    }

    public function getOgTitle()
    {
        return $this->og_title ?? $this->getTitle();
    }

    public function getOgDescription()
    {
        return $this->og_description ?? $this->getMetaDescription();
    }

    public function getTwitterTitle()
    {
        return $this->twitter_title ?? $this->getOgTitle();
    }

    public function getTwitterDescription()
    {
        return $this->twitter_description ?? $this->getOgDescription();
    }

    public function getCanonicalUrl()
    {
        return $this->canonical_url ?? url()->current();
    }

    public function getJsonLd()
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $this->schema_type,
            'name' => $this->getTitle(),
            'description' => $this->getMetaDescription(),
            'url' => $this->getCanonicalUrl(),
            'datePublished' => $this->published_time?->toIso8601String(),
            'dateModified' => $this->modified_time?->toIso8601String(),
        ];

        if ($this->author) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $this->author
            ];
        }

        if ($this->og_image) {
            $schema['image'] = $this->og_image;
        }

        return $schema;
    }

    public function getStructuredData()
    {
        return $this->getJsonLd();
    }
}
