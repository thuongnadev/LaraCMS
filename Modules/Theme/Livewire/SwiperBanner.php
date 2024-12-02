<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Theme\Traits\HandleColorTrait;

class SwiperBanner extends Component
{
    use HandleColorTrait;

    public $banners;
    public $config;
    public $primaryColor;
    public $uniqueId;

    public function mount($config)
    {
        $this->config = $config['component'] ?? [];
        $this->config = $this->transformBannerData(json_decode($this->config['content'], true));
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->uniqueId = 'swiper-' . uniqid();
    }

    public function transformBannerData($inputData)
    {
        $config = [
            'image' => [],
            'title' => '',
            'description' => '',
            'cta_text' => '',
            'cta_link' => '',
            'content_alignment' => $this->config['content_alignment'] ?? 'center',
            'show_pagination' => $this->config['show_pagination'] ?? false,
            'show_navigation' => $this->config['show_navigation'] ?? false,
            'autoplay_speed' => $this->config['autoplay_speed'] ?? 3000,
        ];

        foreach ($inputData as $item) {
            if ($item['type'] === 'banner') {
                $bannerData = $item['data'];

                if (isset($bannerData['image']) && is_array($bannerData['image'])) {
                    $config['image'] = array_merge($config['image'], array_values($bannerData['image']));
                }
                
                $fields = ['title', 'description', 'cta_text', 'cta_link'];
                foreach ($fields as $field) {
                    if (!empty($bannerData[$field])) {
                        $config[$field] = $bannerData[$field];
                    }
                }
            }
        }
 
        return $config;
    }
    
    public function render()
    {
        return view('theme::livewire.swiper-banner');
    }
}