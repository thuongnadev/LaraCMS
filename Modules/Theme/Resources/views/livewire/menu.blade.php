<nav 
    x-data="{ isSticky: false }" 
    x-init="window.addEventListener('scroll', () => { isSticky = window.scrollY > 130 })"
    :class="{ 'bg-white shadow-xl': isSticky }"
    class="border-gray-200 hidden w-full md:block md:w-auto sticky top-0 z-50 transition-all duration-300 ease-in-out"
    id="navbar-multi-level"
    :style="isSticky ? 'background: white;' : 'background: rgb({{ $this->hexToRgb($this->primaryColor) }}, 0.2);'"
>
    <div class="max-w-7xl mx-auto px-2">
        <ul
            class="flex flex-col md:flex-row md:flex-wrap font-semibold p-4 md:p-0 mt-4 border border-gray-100 rtl:space-x-reverse md:mt-0 md:border-0"
        >
            @foreach ($menu->menuItems as $menuItem)
                @livewire('theme::menu-item', ['menuItem' => $menuItem, 'depth' => 1], key($menuItem->id))
            @endforeach
        </ul>
    </div>
</nav>
