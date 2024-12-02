<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Component\Entities\ComponentConfiguration;
use Modules\Form\Entities\Form;
use Modules\Header\Entities\Header;
use Modules\ProductVps\Entities\ProductVps;
use Modules\Theme\Traits\HandleColorTrait;

class ProductDetail extends Component
{
    use HandleColorTrait;

    public $slug;
    public $product;
    public $relatedProducts;
    public $contacts;
    public $config = [];
    public $primaryColor;
    public $selectedProductName;

    public function mount($slug)
    {
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->slug = $slug;
        $this->product = $this->fetchProduct();
        $this->relatedProducts = $this->fetchRelatedProducts();
        $this->contacts = $this->fetchContacts();
    }

    public function fetchProduct()
    {
        $query = ProductVps::with(['categories', 'media']);

        if (isset($this->config['category'])) {
            $categoryIds = json_decode($this->config['category'], true);
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        return $query->where('slug', $this->slug)->firstOrFail();
    }

    public function fetchRelatedProducts()
    {
        $query = ProductVps::with(['categories', 'media'])
            ->where('id', '!=', $this->product->id);

        $categoryIds = $this->product->categories->pluck('id')->toArray();
        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.id', $categoryIds);
        });

        return $query->latest()->limit(8)->get();
    }

    public function fetchContacts()
    {
        $header = Header::where('status', true)->with('contacts')->first();
        return $header ? $header->contacts : collect();
    }

    public function fetchForm()
    {
        $compConfig = ComponentConfiguration::with(['pageComponentConfigurationValues' => function ($query) {
            $query->select('comp_page_values.value', 'comp_page_values.type', 'comp_pages.created_at')
                ->latest()
                ->limit(1);
        }])
            ->where('name', 'form_consulting_product')
            ->first();

        if ($compConfig && $compConfig->pageComponentConfigurationValues->isNotEmpty()) {
            $latestValue = $compConfig->pageComponentConfigurationValues->first();
            $formId = $latestValue->pivot->value;
        }

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

    public function render()
    {
        $url = url()->current();
        $title = $this->product->name;
        $form = $this->fetchForm();
        return view('theme::livewire.product-detail', [
            'product' => $this->product,
            'relatedProducts' => $this->relatedProducts,
            'contacts' => $this->contacts,
            'form' => $form,
            'url' => $url,
            'title' => $title,
        ]);
    }
}
