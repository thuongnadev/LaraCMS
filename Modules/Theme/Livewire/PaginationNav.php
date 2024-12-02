<?php

namespace Modules\Theme\Livewire;

use Livewire\Component;
use Livewire\Attributes\Reactive;
use Livewire\Attributes\Url;
use Modules\Theme\Traits\HandleColorTrait;

class PaginationNav extends Component
{
    use HandleColorTrait;

    #[Reactive]
    public $lastPage;

    #[Reactive]
    public $total;

    #[Reactive]
    public $perPage;

    #[Url(as: 'trang', history: true)]
    public $currentPage = 1;

    public $primaryColor;
    public $primaryColorRgb;

    public function mount($lastPage = null, $total = null, $perPage = null)
    {
        $this->lastPage = $lastPage ?? $this->lastPage;
        $this->total = $total ?? $this->total;
        $this->perPage = $perPage ?? $this->perPage;
        $this->primaryColor = $this->getFilamentPrimaryColor();
        $this->primaryColorRgb = $this->hexToRgb($this->primaryColor);
    }

    public function previousPage()
    {
        if ($this->currentPage > 1) {
            $this->currentPage--;
            $this->dispatch('pageChanged', trang: $this->currentPage);
        }
    }

    public function nextPage()
    {
        if ($this->currentPage < $this->lastPage) {
            $this->currentPage++;
            $this->dispatch('pageChanged', trang: $this->currentPage);
        }
    }

    public function setPage($page)
    {
        $this->currentPage = $page;
        $this->dispatch('pageChanged', trang: $this->currentPage);
    }

    public function render()
    {
        return view('theme::livewire.pagination-nav');
    }
}
