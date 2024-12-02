<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Form\Entities\Form;
use Modules\Theme\Traits\HandleColorTrait;

class Contact extends Component
{
    use HandleColorTrait;

    public string $primaryColor;
    public string $primaryColorRgb;
    public $config;

    public function mount($config) {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        if($this->isHexColor($this->primaryColor)) {
            $this->primaryColorRgb = $this->hexToRgb($this->primaryColor);
        } else {
            $this->primaryColorRgb = $this->primaryColor;
        }

        $this->config = $config['component'] ?? [];
    }

    public function getImages() {
        return !empty($this->config['images']) ? json_decode($this->config['images'], true) : null;
    }

    public function getContactGroup()
    {
        $contactGroup = !empty($this->config['contact_group']) ? json_decode($this->config['contact_group'], true) : null;

        if ($contactGroup) {
            return array_values(array_map(function ($contact) {
                return [
                    'icon' => $contact['icon'],
                    'name' => $contact['name'],
                    'value' => $contact['value'],
                ];
            }, $contactGroup));
        }

        return null;
    }

    public function fetchForm()
    {
        return !empty($this->config['form'])
            ? Form::with([
                'formFields',
                'formFields.fieldValues',
                'submissions',
                'emailSetting',
                'notification'
            ])->find($this->config['form'])
            : null;
    }

    public function render()
    {
        $form = $this->fetchForm();
        $images = $this->getImages();
        $iframeCode = $this->config['iframe'] ?? null;
        $contactGroup = $this->getContactGroup();

        return view('theme::livewire.contact', [
            'form' => $form,
            'images' => $images,
            'iframeCode' => $iframeCode,
            'contactGroup' => $contactGroup
        ]);
    }
}