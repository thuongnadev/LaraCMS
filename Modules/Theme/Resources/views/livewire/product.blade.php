<div class="max-w-7xl mx-auto px-2">
    <div class="flex flex-wrap -mx-4">
        <div x-data="{ open: $persist(true).as('category-menu-open') }" class="w-full lg:w-1/5 px-4 mb-8 lg:mb-0">
            <div class="bg-gray-50 rounded-xl p-4" style="border: 1px solid {{ $primaryColor }};">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold" style="color: {{ $primaryColor }}">Danh mục</h2>
                    <button @click="open = !open" class="lg:hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform"
                            :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <ul x-show="open" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-y-90"
                    x-transition:enter-end="opacity-100 transform scale-y-100"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform scale-y-100"
                    x-transition:leave-end="opacity-0 transform scale-y-90" class="lg:!block">
                    <li class="mb-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" wire:model.live="selectedCategory" value="" class="hidden">
                            <span class="custom-radio mr-2"></span>
                            <span>Tất cả</span>
                        </label>
                    </li>
                    @foreach ($categories as $category)
                        <x-theme::category-item :category="$category" :level="0" />
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="w-full lg:w-4/5 px-4">
            <div class="mb-6 flex flex-wrap items-center justify-between bg-gray-50 p-4 rounded-xl"
                style="border: 1px solid {{ $primaryColor }};">
                <div class="w-full md:w-auto mb-4 md:mb-0">
                    <span class="font-medium mr-2">Sắp xếp theo:</span>
                    <button wire:click="$set('sortBy', 'A-Z')"
                        class="px-3 py-1 rounded-full text-sm {{ $sortBy == 'A-Z' ? 'text-white bg-gray-900' : 'text-black bg-white' }}">
                        A - Z
                    </button>
                    <button wire:click="$set('sortBy', 'Z-A')"
                        class="px-3 py-1 rounded-full text-sm {{ $sortBy == 'Z-A' ? 'text-white bg-gray-900' : 'text-black bg-white' }}">
                        Z - A
                    </button>
                    <button wire:click="$set('sortBy', 'newest')"
                        class="px-3 py-1 rounded-full text-sm {{ $sortBy == 'newest' ? 'text-white bg-gray-900' : 'text-black bg-white' }}">
                        Mới nhất
                    </button>
                </div>
                <div class="w-full md:w-auto">
                    <input wire:model.live="search" type="text" placeholder="Tìm kiếm sản phẩm..."
                        class="w-full md:w-64 px-3 py-2 rounded-md outline-none focus:ring-2 focus:ring-opacity-50 transition-all duration-300 ease-in-out"
                        style="--tw-ring-color: {{ $primaryColor }}; border: 1px solid {{ $primaryColor }};">
                </div>
            </div>

            <div
                class="grid gap-4 grid-cols-1 
            @if ($smColumns == 1) sm:grid-cols-1
            @elseif($smColumns == 2) sm:grid-cols-2
            @elseif($smColumns == 3) sm:grid-cols-3 @endif
            @if ($mdColumns == 1) md:grid-cols-1
            @elseif($mdColumns == 2) md:grid-cols-2
            @elseif($mdColumns == 3) md:grid-cols-3 @endif
            @if ($lgColumns == 1) lg:grid-cols-1
            @elseif($lgColumns == 2) lg:grid-cols-2
            @elseif($lgColumns == 3) lg:grid-cols-3 @endif">
                @foreach ($products as $product)
                    <div class="relative overflow-hidden border-2 rounded-xl h-[500px] product-card cursor-pointer">
                        <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                            @if ($product->productImage->isNotEmpty())
                                <img src="{{ asset('/storage/' . $product->productImage->first()->file_path) }}"
                                    loading="lazy"
                                    alt="{{ $product->productImage->first()->alt_text ?? $product->name }}"
                                    style="transition-duration:8000ms">
                            @endif
                            <div class="p-4">
                                <div class="text-base font-semibold mb-2">{{ $product->name }}</div>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($product->categories as $category)
                                        <span class="text-xs text-white px-2 py-1 rounded"
                                            style="background-color: {{ $primaryColor }}">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 text-center p-4 bg-white">
                                <div class="text-base font-semibold">{{ $product->name }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
                @if (count($products) == 0)
                    <div class="col-span-full text-center py-8">
                        <p class="text-lg text-gray-500">Không tìm thấy sản phẩm nào.</p>
                    </div>
                @endif
            </div>

            <div class="mt-10 flex justify-center">
                <livewire-theme::pagination-nav :last-page="$products-> lastPage()" :total="$products-> total()"
                    :per-page="$perPage" />
            </div>
        </div>
    </div>

    <style>
        .custom-radio {
            width: 16px;
            height: 16px;
            border: 2px solid #ccc;
            border-radius: 50%;
            display: inline-block;
            position: relative;
        }

        .custom-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            background-color: {{ $primaryColor }};
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: transform 0.2s ease-in-out;
        }

        input[type="radio"]:checked+.custom-radio::after {
            transform: translate(-50%, -50%) scale(1);
        }
    </style>
</div>
