<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Theme\Traits\HandleColorTrait;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Post\Entities\Post;

class SwiperSlide extends Component
{
    use HandleColorTrait, HandleCalculateTrait;

    public $posts;
    public $config;
    public $primaryColor;
    public $primaryColorRgb;
    public $uniqueId;

    public function mount($config)
    {
        $this->config = $config ?? [];
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->primaryColorRgb = $this->hexToRgb($this->primaryColor);
        $this->posts = $this->fetchData();
        $this->uniqueId = 'swiper-' . uniqid();
    }

    public function fetchData()
    {
        $query = Post::with(['tags', 'categories', 'media']);
        
        $limit = $this->config['limit_post'] ?? 5;

        $query->where('status', 'published');

        if (isset($this->config['posts'])) {
            $postsIds = explode(',', $this->config['posts']);
            if (is_array($postsIds) && !empty($postsIds)) {
                $query->whereIn('id', $postsIds);
            }
        }

        if (isset($this->config['category'])) {
            $categoryIds = explode(',', $this->config['category']);
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        return $query->latest()->take($limit)->get();
    }

    public function calculateColumns($default = 4)
    {
        return $this->calculateColumnsTrait($this->config, $default);
    }

    public function render()
    {
        return view('theme::livewire.swiper-slide', [
            'columns' => $this->calculateColumns(),
        ]);
    }
}
