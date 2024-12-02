<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;

class Pricing extends Component
{
    public $config;
    
    public function mount($config)
    {
        $this->config = $config['component'] ?? [];
    }

    public function render()
    {
        return view('theme::livewire.pricing');
    }
}
