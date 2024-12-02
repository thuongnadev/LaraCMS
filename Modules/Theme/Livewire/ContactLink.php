<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\LiveChat\Entities\ContactLink as ContactLinkModel;

class ContactLink extends Component
{
    public $contactLinks;

    public function mount()
    {
        $this->contactLinks = ContactLinkModel::latest()->first();
    }

    public function render()
    {
        return view('theme::livewire.contact-link');
    }
}
