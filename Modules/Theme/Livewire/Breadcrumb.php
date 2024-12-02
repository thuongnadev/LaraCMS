<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Menu\Entities\MenuItem;
use Illuminate\Support\Facades\Request;
use Modules\Theme\Traits\HandleColorTrait;

class Breadcrumb extends Component
{
    use HandleColorTrait;
    
    public $primaryColor;
    public $breadcrumbs = [];
    public $slug;
    
    public function mount($slug = null, $name = null)
    {
        $this->slug = $slug;
        
        if ($this->slug) {
            $this->breadcrumbs = [
                ['title' => $name, 'url' => url($this->slug)]
            ];
        } else {
            $this->generateBreadcrumbs();
        }
        
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }
    
    private function generateBreadcrumbs()
    {
        $currentUrl = Request::url();
        $menuItems = MenuItem::with('children')->orderBy('order')->get();
        $this->breadcrumbs = $this->findBreadcrumbPath($menuItems, $currentUrl);
    }
    
    private function findBreadcrumbPath($items, $targetUrl, $path = [])
    {
        foreach ($items as $item) {
            if ($item->target === '_parent') {
                continue;
            }

            $itemUrl = $item->getFullUrl();
            $newPath = array_merge($path, [['title' => $item->title, 'url' => $itemUrl]]);
            
            if ($itemUrl === $targetUrl) {
                return $newPath;
            }
            
            if ($item->children->isNotEmpty()) {
                $result = $this->findBreadcrumbPath($item->children, $targetUrl, $newPath);
                if ($result) {
                    return $result;
                }
            }
        }
        
        return null;
    }
    
    public function render()
    {
        return view('theme::livewire.breadcrumb');
    }
}