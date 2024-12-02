<li class="mb-2" x-data="{ open: false }">
    <div class="flex items-center">
        @if($category->children->isNotEmpty())
            <button @click="open = !open" class="mr-2 text-gray-500 focus:outline-none" aria-label="Toggle subcategories">
                <svg x-show="!open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <svg x-show="open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        @else
            <span class="w-4 h-4 mr-2"></span>
        @endif
        <label class="flex items-center cursor-pointer">
            <input type="radio" wire:model.live="selectedCategory" value="{{ $category->slug }}" class="hidden">
            <span class="custom-radio mr-2"></span>
            <span>{{ $category->name }}</span>
        </label>
    </div>
    @if($category->children->isNotEmpty())
        <ul x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="ml-6 mt-2">
            @foreach($category->children as $childCategory)
                <x-theme::category-item :category="$childCategory" :level="$level + 1" />
            @endforeach
        </ul>
    @endif
</li>