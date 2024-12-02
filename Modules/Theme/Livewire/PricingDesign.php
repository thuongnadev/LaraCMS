<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Pricing\Entities\PricingGroup;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Theme\Traits\HandleColorTrait;
use Illuminate\Support\Str;
use Modules\Form\Entities\Form;

class PricingDesign extends Component
{
    use HandleColorTrait,
        HandleCalculateTrait;

    public $config;
    public $primaryColor;
    public $activeTab = '';
    public $pricingPackages = [];
    public $selectedPlan = '';
    public $smColumns;
    public $mdColumns;
    public $lgColumns;
    public $selectedPlanName = '';

    public function mount($config)
    {
        $this->config = $config ?? [];
        $this->calculateColumns();
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->fetchPricingData();
        $this->setDefaultActiveTab();
    }

    private function setDefaultActiveTab()
    {
        if (empty($this->activeTab) && !empty($this->pricingPackages)) {
            $this->activeTab = array_key_first($this->pricingPackages);
        }
    }

    public function fetchPricingData()
    {
        $idPricingGroup = $this->config['pricing_group'] ?? 0;
        $idPricingTypes = explode(',', $this->config['pricing_type'] ?? '');
        $idPricingTypes = array_map('trim', $idPricingTypes);
        $idPricingTypes = array_filter($idPricingTypes);

        $pricingGroups = PricingGroup::with([
            'pricingTypes' => function ($query) use ($idPricingTypes) {
                $query->whereIn('id', $idPricingTypes);
            },
            'pricingTypes.pricings' => function ($query) {
                $query->where('show', true);
            },
            'pricingTypes.pricings.pricingKeyValues.pricingKey',
            'pricingTypes.pricings.pricingKeyValues.pricingValue',
            'pricingTypes.pricings.pricingKeyValues.pricingContent'
        ])
            ->where('id', $idPricingGroup)
            ->get();

        $this->pricingPackages = [];

        foreach ($pricingGroups as $group) {
            foreach ($group->pricingTypes as $type) {
                $packageKey = Str::slug($type->name);
                if (!isset($this->pricingPackages[$packageKey])) {
                    $this->pricingPackages[$packageKey] = [
                        'name' => $type->name,
                        'plans' => []
                    ];
                }

                foreach ($type->pricings as $pricing) {
                    $plan = [
                        'name' => $pricing->name,
                        'features' => []
                    ];

                    foreach ($pricing->pricingKeyValues as $keyValue) {
                        $isIncluded = $keyValue->pricingValue->content === 'w-true';
                        $plan['features'][] = [
                            'name' => $keyValue->pricingKey->name,
                            'included' => $isIncluded,
                            'description' => $keyValue->pricingKey->name
                        ];
                    }

                    $this->pricingPackages[$packageKey]['plans'][] = $plan;
                }
            }
        }
    }

    public function calculateColumns()
    {
        $columns = $this->calculateColumnsTrait($this->config);
        $this->smColumns = $columns['sm'];
        $this->mdColumns = $columns['md'];
        $this->lgColumns = $columns['lg'];
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('tab-changed', tab: $tab);
    }


    public function fetchForm()
    {
        $formId = $this->config['form_consulting_pricing'] ?? null;

        if ($formId) {
            return Form::with([
                'formFields',
                'formFields.fieldValues',
                'submissions',
                'emailSetting',
                'notification'
            ])->find($formId);
        }

        return null;
    }

    public function setSelectedPlanName($name)
    {
        $this->selectedPlanName = $name;
    }

    public function resetSelectedPlanName()
    {
        $this->selectedPlanName = '';
    }

    public function render()
    {
        $form = $this->fetchForm();
        return view('theme::livewire.pricing-design', [
            'form' => $form,
        ]);
    }
}
