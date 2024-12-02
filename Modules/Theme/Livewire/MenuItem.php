<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Theme\Traits\HandleColorTrait;

class MenuItem extends Component
{
    use HandleColorTrait;

    public $menuItem;
    public $depth;
    public $primaryColor;

    public function mount($menuItem, $depth = 1)
    {
        $this->menuItem = $menuItem;
        $this->depth = $depth;
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }

    public function render()
    {
        return view('theme::livewire.menu-item');
    }
}
