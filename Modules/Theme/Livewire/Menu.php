<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Menu\Entities\Menu as EntitiesMenu;
use Modules\Theme\Traits\HandleColorTrait;

class Menu extends Component
{
    use HandleColorTrait;
    
    public $primaryColor;

    public function mount()
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }

    public function render()
    {
        $menu = EntitiesMenu::query()
            ->with([
                'menuItems' => function ($query) {
                    $query->whereNull('parent_id')
                        ->orderBy('order')
                        ->with(['children' => function ($query) {
                            $query->orderBy('order')
                                ->with(['children' => function ($query) {
                                    $query->orderBy('order');
                                }]);
                        }]);
                },
                'locations' => function ($query) {
                    $query->where('location', 'header');
                }
            ])
            ->whereHas('locations', function ($query) {
                $query->where('location', 'header');
            })
            ->where('is_visible', true)
            ->first();

        return view('theme::livewire.menu', [
            'menu' => $menu
        ]);
    }
}
