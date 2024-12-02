<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Header\Entities\Header as HeaderEntity;
use Modules\Theme\Traits\HandleColorTrait;

class Header extends Component
{
    use HandleColorTrait;
    
    public $primaryColor;

    public array $headerConfig = [];

    public function mount(): void {
        $this->headerConfig = $this->getHeaderConfigs();
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }

    private function getHeaderConfigs(): array {
        $header = HeaderEntity::with('contacts')->where('status', 1)->first();

        if(!$header) {
            return [];
        }

        return [
            'name' => $header->name,
            'logo' => $header->logo,
            'logo_size' => $header->logo_size,
            'background_color' => $header->background_color,
            'contacts' => $header->contacts?->map(function ($contact){
                return [
                    'name' => $contact->name,
                    'value' => $contact->value
                ];
            })->toArray()
        ];
    }

    public function render()
    {
        return view('theme::livewire.header', [
            'headerConfig' => $this->headerConfig
        ]);
    }
}