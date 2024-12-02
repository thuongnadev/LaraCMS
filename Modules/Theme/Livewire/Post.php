<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Post extends Component
{
    public $config;
    
    public function mount($config)
    {
        $this->config = $config['component'] ?? [];
        $this->updateCacheIfNeeded();
    }

    private function updateCacheIfNeeded()
    {
        $cacheKey = 'post_config_style';
        $cachedStyle = Cache::get($cacheKey) ?? 'default';
        
        $currentStyle = isset($this->config['style']) ? $this->config['style'] : 'default';
        
        if ($cachedStyle === null || $cachedStyle !== $currentStyle) {
            Cache::forever($cacheKey, $currentStyle);
        }
    }
    
    public function render()
    {
        return view('theme::livewire.post');
    }
}
