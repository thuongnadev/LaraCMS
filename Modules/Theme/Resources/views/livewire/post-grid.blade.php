<div>
    <div
        class="grid gap-4 grid-cols-1 
        @if ($smColumns == 1) sm:grid-cols-1
        @elseif($smColumns == 2) sm:grid-cols-2
        @elseif($smColumns == 3) sm:grid-cols-3
        @elseif($smColumns == 4) sm:grid-cols-4 @endif
        @if ($mdColumns == 1) md:grid-cols-1
        @elseif($mdColumns == 2) md:grid-cols-2
        @elseif($mdColumns == 3) md:grid-cols-3
        @elseif($mdColumns == 4) md:grid-cols-4 @endif
        @if ($lgColumns == 1) lg:grid-cols-1
        @elseif($lgColumns == 2) lg:grid-cols-2
        @elseif($lgColumns == 3) lg:grid-cols-3
        @elseif($lgColumns == 4) lg:grid-cols-4 @endif">
        @foreach ($posts as $post)
            @switch($config['style'] ?? 'default')
                @case('overlay')
                    <div
                        class="relative overflow-hidden rounded-xl shadow-md h-64 transition-transform duration-300 ease-in-out hover:-translate-y-1">
                        <a href="{{ route('post.detail', ['slug' => $post->slug]) }}">
                            @if ($post->media->isNotEmpty())
                                <img src="{{ asset('/storage/' . $post->media->first()->file_path) }}"
                                    loading="lazy"
                                    alt="{{ $post->media->first()->alt_text ?? $post->title }}"
                                    class="w-full h-full object-cover">
                            @endif
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-4">
                                <div class="text-base font-semibold mb-2 text-white">{{ $post->title }}</div>
                                <p class="text-gray-300">{{ Str::limit($post->summary, 60, '...') }}</p>
                                @if ($post->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($post->tags as $tag)
                                            @if (!empty(trim($tag->name)))
                                                <span class="text-xs text-white px-2 py-1 rounded"
                                                    style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                    {{ $tag->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if ($post->categories->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($post->categories as $category)
                                            @if (!empty(trim($category->name)))
                                                <span class="text-xs text-white px-2 py-1 rounded"
                                                    style="{{ !empty(trim($category->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                    {{ $category->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @break

                @case('card')
                    <div class="overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1">
                        <a href="{{ route('post.detail', ['slug' => $post->slug]) }}">
                            @if ($post->media->isNotEmpty())
                                <img src="{{ asset('/storage/' . $post->media->first()->file_path) }}"
                                    alt="{{ $post->media->first()->alt_text ?? $post->title }}"
                                    loading="lazy"
                                    class="w-full h-48 object-cover rounded-xl">
                            @endif
                            <div class="text-base font-semibold my-2">{{ $post->title }}</div>
                            <p class="text-gray-600">{{ Str::limit($post->summary, 60, '...') }}</p>
                            @if ($post->tags->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($post->tags as $tag)
                                        @if (!empty(trim($tag->name)))
                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if ($post->categories->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($post->categories as $category)
                                        @if (!empty(trim($category->name)))
                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                style="{{ !empty(trim($category->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                {{ $category->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    </div>
                @break

                @case('minimal')
                    <div
                        class="border-b border-gray-200 pb-4 transition-transform duration-300 ease-in-out hover:-translate-y-1">
                        <a href="{{ route('post.detail', ['slug' => $post->slug]) }}">
                            <div class="text-base font-semibold mb-2">{{ $post->title }}</div>
                            <p class="text-gray-600">{{ Str::limit($post->summary, 60, '...') }}</p>
                            @if ($post->tags->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($post->tags as $tag)
                                        @if (!empty(trim($tag->name)))
                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                {{ $tag->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            @if ($post->categories->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($post->categories as $category)
                                        @if (!empty(trim($category->name)))
                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                style="{{ !empty(trim($category->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                {{ $category->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </a>
                    </div>
                @break

                @default
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1">
                        <a href="{{ route('post.detail', ['slug' => $post->slug]) }}">
                            @if ($post->media->isNotEmpty())
                                <img src="{{ asset('/storage/' . $post->media->first()->file_path) }}"
                                    loading="lazy"
                                    alt="{{ $post->media->first()->alt_text ?? $post->title }}"
                                    class="w-full h-48 object-cover">
                            @endif
                            <div class="p-4">
                                <div class="text-base font-semibold mb-2">{{ $post->title }}</div>
                                <p class="text-gray-600">{{ Str::limit($post->summary, 60, '...') }}</p>
                                @if ($post->tags->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($post->tags as $tag)
                                            @if (!empty(trim($tag->name)))
                                                <span class="text-xs text-white px-2 py-1 rounded"
                                                    style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                    {{ $tag->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if ($post->categories->isNotEmpty())
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($post->categories as $category)
                                            @if (!empty(trim($category->name)))
                                                <span class="text-xs text-white px-2 py-1 rounded"
                                                    style="{{ !empty(trim($category->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                    {{ $category->name }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
            @endswitch
        @endforeach
    </div>
    @if(!empty($config['show_pagination']) && $config['show_pagination'])
        <div class="mt-10 flex justify-center">
            <livewire-theme::pagination-nav :last-page="$posts->lastPage()" :total="$posts->total()"
                                            :per-page="$perPage" />
        </div>
    @endif
</div>
