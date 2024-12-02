<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Pricing\Entities\PricingGroup;
use Modules\Theme\Traits\HandleColorTrait;

class DomainPricing extends Component
{
    use HandleColorTrait;
    public $config;
    public $primaryColor;
    public $columns = [];
    public $pricingData = [];

    public function mount($config)
    {
        $this->config = $config ?? [];
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->fetchPricingData();
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

        $this->columns = [];
        $this->pricingData = [];

        foreach ($pricingGroups as $group) {
            foreach ($group->pricingTypes as $type) {
                foreach ($type->pricings as $pricing) {
                    $keyValuePairs = [];
                    foreach ($pricing->pricingKeyValues as $keyValue) {
                        $columnName = $keyValue->pricingKey->name;
                        if (!in_array($columnName, $this->columns)) {
                            $this->columns[] = $columnName;
                        }
                        $keyValuePairs[$columnName] = $keyValue->pricingContent->meta;
                    }

                    $domainColumn = $this->columns[0];
                    $domains = $keyValuePairs[$domainColumn] ?? [];

                    foreach ($domains as $index => $domain) {
                        if (!isset($this->pricingData[$domain])) {
                            $this->pricingData[$domain] = [];
                        }
                        foreach ($this->columns as $column) {
                            $this->pricingData[$domain][$column] = $keyValuePairs[$column][$index] ?? 'N/A';
                        }
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('theme::livewire.domain-pricing');
    }
}
