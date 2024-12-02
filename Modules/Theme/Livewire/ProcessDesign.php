<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Process\Entities\Step;
use Modules\Theme\Traits\HandleColorTrait;

class ProcessDesign extends Component
{
    use HandleColorTrait;

    public $config;
    public $primaryColor;
    public $steps;

    public function mount($config)
    {
        $this->config = $config['component'] ?? [];
        $this->steps = $this->fetchData();
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }

    public function fetchData()
    {
        if(!empty($this->config['category'])) {
            return Step::where('process_id', $this->config['category'])
                ->orderBy('order')
                ->get()
                ->toArray();
        }
        return [];
    }

    public function render()
    {
        return view('theme::livewire.process-design');
    }
}
