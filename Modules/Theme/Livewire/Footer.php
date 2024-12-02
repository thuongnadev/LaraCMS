<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Footer\Entities\Footer as FooterEntity;
use Modules\Header\Entities\Header as HeaderEntity;

class Footer extends Component
{
    public $footerConfig;

    public function mount() {
        $this->footerConfig = $this->getFooterConfig();
    }

    private function getFooterConfig(): array
    {
        $header = HeaderEntity::where('status', 1)->first();
        $footer = FooterEntity::with([
            'columns',
            'columns.socialMedia',
            'columns.menu.menuItems',
            'columns.businesses'
        ])->where('status', true)->first();

        if (!$footer) {
            return [];
        }

        return [
            'name' => $footer->name,
            'background_color' => $footer->background_color,
            'title_color' => $footer->title_color,
            'base_color' => $footer->base_color,
            'logo_size' => $header->logo_size,
            'columns' => $footer->columns->map(function ($column) {
                return [
                    'title' => $column->title,
                    'content_type' => $column->content_type,
                    'text_content' => $column->text_content,
                    'image_content' => $column->image_content,
                    'iframe_content' => $column->iframe_content,
                    'google_map' => $column->google_map,

                    'menu' => $column->menu_id ? [
                        'name' => $column->menu->name,
                        'items' => $this->formatMenuItems($column->menu->menuItems),
                    ] : null,

                    'business' => $column->business_id ? [
                        'name' => $column->businesses->name,
                        'address' => $column->businesses->address,
                        'phone' => $column->businesses->phone,
                        'email' => $column->businesses->email,
                        'website' => $column->businesses->website,
                    ] : null,
                    'social_media' => $column->socialMedia->map(function ($social) {
                        return [
                            'platform' => $social->platform,
                            'icon' => $social->icon,
                            'url' => $social->url,
                        ];
                    })->toArray(),
                ];
            })->toArray(),
        ];
    }

    private function formatMenuItems($menuItems, $parentId = null): array
    {
        $items = [];
    
        foreach ($menuItems as $item) {
            if ($item->parent_id === $parentId && $item->target !== '_parent') {
                $children = $this->formatMenuItems($menuItems, $item->id);
    
                $items[] = [
                    'title' => $item->title,
                    'url' => $item->url,
                    'target' => $item->target,
                    'order' => $item->order,
                    'children' => $children,
                ];
            }
        }
    
        return $items;
    }

    public function render()
    {
        return view('theme::livewire.footer', [
            'footerConfig' => $this->footerConfig
        ]);
    }
}