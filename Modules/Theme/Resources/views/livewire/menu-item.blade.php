<li x-data="{ open: false }" @mouseleave="open = false" class="relative group">
    @php
        $isActive = function ($item) {
            $currentUrl = request()->url();
            if ($currentUrl == $item->getFullUrl()) {
                return true;
            }
            return $item->children->contains(function ($child) use ($currentUrl) {
                return $currentUrl == $child->getFullUrl() ||
                    $child->children->contains(function ($grandchild) use ($currentUrl) {
                        return $currentUrl == $grandchild->getFullUrl();
                    });
            });
        };

        $menuItemActive = $isActive($menuItem);
        
        $hoverColor = 'rgba(' . implode(',', sscanf($primaryColor, '#%02x%02x%02x')) . ', 0.5)';
    @endphp

    @if ($menuItem->children->isEmpty())
        <a href="{{ $menuItem->getFullUrl() }}"
            class="hover:text-gray-900 block py-3 px-5 rounded-none transition-colors duration-300 ease-in-out whitespace-nowrap overflow-hidden text-ellipsis
                  {{ $menuItemActive ? 'text-white' : '' }} hover:bg-opacity-10"
            style="{{ $menuItemActive ? 'background-color: ' . $primaryColor . ';' : '' }}"
            onmouseover="this.style.backgroundColor='{{ $hoverColor }}'"
            onmouseout="this.style.backgroundColor='{{ $menuItemActive ? $primaryColor : 'transparent' }}'">
            {{ $menuItem->title }}
        </a>
    @else
        <button @click="open = !open" @mouseenter="open = true"
            class="hover:text-gray-900 flex items-center justify-between w-full py-3 px-5 md:w-auto transition-colors duration-300 ease-in-out whitespace-nowrap overflow-hidden text-ellipsis
                       {{ $menuItemActive ? 'text-white' : '' }} hover:bg-opacity-10"
            style="{{ $menuItemActive ? 'background-color: ' . $primaryColor . ';' : '' }}"
            onmouseover="this.style.backgroundColor='{{ $hoverColor }}'"
            onmouseout="this.style.backgroundColor='{{ $menuItemActive ? $primaryColor : 'transparent' }}'">
            <span class="flex-grow text-left overflow-hidden text-ellipsis">{{ $menuItem->title }}</span>
            <svg class="w-2.5 h-2.5 ms-2.5 flex-shrink-0 transition-transform duration-300"
                :class="{ 'rotate-180': open }" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
            </svg>
        </button>
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95" @mouseenter="open = true" @mouseleave="open = false"
            class="absolute left-0 top-full z-10 font-normal rounded-b-lg bg-white divide-y divide-gray-100 shadow-lg w-auto min-w-[200px]">
            <ul class="py-2 text-sm">
                @foreach ($menuItem->children as $child)
                    @php
                        $childActive = $isActive($child);
                    @endphp
                    <li x-data="{ subOpen: false }" @mouseleave="subOpen = false" class="relative group">
                        @if ($child->children->isEmpty())
                            <a href="{{ $child->getFullUrl() }}"
                                class="hover:text-gray-900 block px-4 py-2 transition-colors duration-200 whitespace-nowrap overflow-hidden text-ellipsis
                                      {{ $childActive ? 'text-white' : '' }} hover:bg-opacity-10"
                                style="{{ $childActive ? 'background-color: ' . $primaryColor . ';' : '' }}"
                                onmouseover="this.style.backgroundColor='{{ $hoverColor }}'"
                                onmouseout="this.style.backgroundColor='{{ $childActive ? $primaryColor : 'transparent' }}'">
                                {{ $child->title }}
                            </a>
                        @else
                            <button @click="subOpen = !subOpen" @mouseenter="subOpen = true"
                                class="flex items-center justify-between w-full px-4 py-2 transition-colors duration-200 whitespace-nowrap overflow-hidden text-ellipsis
                                           {{ $childActive ? 'text-white' : '' }} hover:bg-opacity-10"
                                style="{{ $childActive ? 'background-color: ' . $primaryColor . ';' : '' }}"
                                onmouseover="this.style.backgroundColor='{{ $hoverColor }}'"
                                onmouseout="this.style.backgroundColor='{{ $childActive ? $primaryColor : 'transparent' }}'">
                                <span
                                    class="flex-grow text-left overflow-hidden text-ellipsis">{{ $child->title }}</span>
                                <svg class="w-2.5 h-2.5 ms-2.5 flex-shrink-0 transition-transform duration-300"
                                    :class="{ 'rotate-90': subOpen }" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>
                            <!-- Submenu content (unchanged) -->
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</li>
