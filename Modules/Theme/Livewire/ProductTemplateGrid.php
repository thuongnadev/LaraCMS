<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Category\Entities\Category;
use Modules\Form\Entities\Form;
use Modules\ProductVps\Entities\ProductVps;
use Modules\Tag\Entities\Tag;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Theme\Traits\HandleColorTrait;

class ProductTemplateGrid extends Component
{
    use HandleColorTrait,
        HandleCalculateTrait;

    public $config;
    public $primaryColor;
    public $smColumns;
    public $mdColumns;
    public $lgColumns;
    public $selectedProductName = '';
    public $linkMoreProduct = '';

    public function mount($config)
    {
        $this->linkMoreProduct = $this->standardizeProductRoute($this->config['component']['link_more_product'] ?? '');
        $this->config = $config ?? [];
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->calculateColumns();
    }

    protected function standardizeProductRoute($linkMoreProduct)
    {
        $linkMoreProduct = ltrim($linkMoreProduct, '/');

        if (strpos($linkMoreProduct, 'page.') !== 0) {
            $linkMoreProduct = 'page.' . $linkMoreProduct;
        }

        return $linkMoreProduct;
    }

    public function calculateColumns()
    {
        $columns = $this->calculateColumnsTrait($this->config);
        $this->smColumns = $columns['sm'];
        $this->mdColumns = $columns['md'];
        $this->lgColumns = $columns['lg'];
    }

    public function fetchForm()
    {
        $formId = $this->config['component']['form_consulting_design_sample'] ?? null;
        
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

    public function fetchProducts()
    {
        $query = ProductVps::with(['categories', 'media']);

        if (isset($this->config['products'])) {
            $productsIds = json_decode($this->config['products'], true);
            if (is_array($productsIds) && !empty($productsIds)) {
                $query->whereIn('id', $productsIds);
            }
        }

        return $query->latest()->take($this->config['limit_product'] ?? 4)->get();
    }

    public function render()
    {
        $products = $this->fetchProducts();
        $form = $this->fetchForm();
        $categories = Category::all();
        $tags = Tag::all();

        return view('theme::livewire.product-template-grid', [
            'products' => $products,
            'categories' => $categories,
            'tags' => $tags,
            'form' => $form,
        ]);
    }
}
