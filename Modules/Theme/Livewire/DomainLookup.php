<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Theme\Traits\HandleColorTrait;
use Modules\Theme\Traits\HandleDomainTrait;

class DomainLookup extends Component
{
    use HandleColorTrait,
        HandleCalculateTrait,
        HandleDomainTrait;

    public string $primaryColor;
    public array $configs = [];
    public string $domain = '';

    protected $rules = [
        'domain' => 'required|regex:/^(xn--)?[a-zA-Z0-9\-]+(\.[a-zA-Z0-9\-]+)*$/',
    ];

    protected $messages = [
        'domain.required' => '* Vui lòng nhập tên miền.',
        'domain.regex' => '* Tên miền không đúng định dạng hợp lệ.',
    ];

    public function mount($config)
    {
        $this->configs = $config['component'] ?? [];
        $this->updateCachedConfigs();
        $this->primaryColor = $this->getFilamentPrimaryColor();
    }

    public function redirectToDomainLookupDetail()
    {
        $this->validate();

        $this->redirect(route('domain-lookup.detail', [
            'domain' => $this->domain
        ]));
    }

    public function render()
    {
        return view('theme::livewire.domain-lookup');
    }
}