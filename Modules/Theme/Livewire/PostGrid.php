<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Modules\Theme\Traits\HandleColorTrait;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Modules\Post\Entities\Post;
use Modules\Theme\Traits\HandleCalculateTrait;

class PostGrid extends Component
{
    use HandleColorTrait,
        HandleCalculateTrait;

    public $config;
    public $primaryColor;
    public $perPage = 10;
    public $smColumns;
    public $mdColumns;
    public $lgColumns;

    #[Url(as: 'trang', history: true)]
    public $page = 1;

    public function mount($config)
    {
        $this->config = $config ?? [];
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->calculateColumns();
        $this->perPage = $config['per_page'] ?? $this->perPage;
    }

    public function fetchData()
    {
        $query = Post::with(['tags', 'categories', 'media']);

        $query->where('status', 'published');

        if (isset($this->config['category'])) {
            $categoryIds = explode(',', $this->config['category']);
            if (is_array($categoryIds) && !empty($categoryIds)) {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        return $query->latest()->paginate($this->perPage, ['*'], 'trang', $this->page);
    }

    public function calculateColumns()
    {
        $columns = $this->calculateColumnsTrait($this->config);
        $this->smColumns = $columns['sm'];
        $this->mdColumns = $columns['md'];
        $this->lgColumns = $columns['lg'];
    }

    #[On('pageChanged')]
    public function updatePage($trang)
    {
        $this->page = $trang;
    }

    public function render()
    {
        $posts = $this->fetchData();
        return view(
            'theme::livewire.post-grid',
            [
                'posts' => $posts,
            ]
        );
    }
}
