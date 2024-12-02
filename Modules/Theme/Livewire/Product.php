<?php
namespace Modules\Theme\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Category\Entities\Category;
use Modules\ProductVps\Entities\ProductVps;
use Modules\Theme\Traits\HandleCalculateTrait;
use Modules\Theme\Traits\HandleColorTrait;

class Product extends Component
{
    use HandleColorTrait, HandleCalculateTrait, WithPagination;

    public $config;
    public $primaryColor;
    public $perPage = 9;
    public $smColumns;
    public $mdColumns;
    public $lgColumns;

    #[Url(as: 'trang', history: true)]
    public $page = 1;
    
    #[Url(as: 'tim-kiem')]
    public $search = '';
    
    #[Url(as: 'danh-muc')]
    public $selectedCategory = '';
    
    #[Url(as: 'sap-xep')]
    public $sortBy = 'newest';

    protected $queryString = [
        'tim-kiem' => ['as' => 'search', 'except' => ''],
        'danh-muc' => ['as' => 'selectedCategory', 'except' => ''],
        'sap-xep' => ['as' => 'sortBy', 'except' => 'newest'],
        'trang' => ['as' => 'page', 'except' => 1],
    ];

    public function mount($config)
    {
        $this->config = $config ?? [];
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->calculateColumns();
        $this->perPage = $config['per_page'] ?? $this->perPage;
    }

    public function calculateColumns()
    {
        $columns = $this->calculateColumnsTrait($this->config, 3);
        $this->smColumns = $columns['sm'];
        $this->mdColumns = $columns['md'];
        $this->lgColumns = $columns['lg'];
    }

    public function fetchCategories()
    {
        return Category::where('category_type', 'product')
            ->whereNull('parent_id')
            ->with('children')
            ->select('id', 'name', 'slug', 'parent_id')
            ->get();
    }

    public function fetchProducts()
    {
        $query = ProductVps::with(['categories', 'media']);

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->selectedCategory)) {
            $query->whereHas('categories', function ($q) {
                $q->where('categories.slug', $this->selectedCategory);
            });
        }

        switch ($this->sortBy) {
            case 'A-Z':
                $query->orderBy('name', 'asc');
                break;
            case 'Z-A':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        return $query->latest()->paginate($this->perPage, ['*'], 'trang', $this->page);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    #[On('pageChanged')]
    public function updatePage($trang)
    {
        $this->page = $trang;
    }
    
    public function render()
    {
        $products = $this->fetchProducts();
        $categories = $this->fetchCategories();
        return view('theme::livewire.product', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}