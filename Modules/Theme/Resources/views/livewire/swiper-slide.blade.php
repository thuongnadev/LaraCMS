<div class="swiper {{ $uniqueId }}">
    <div class="swiper-wrapper">
        @foreach ($posts as $post)
            <div class="swiper-slide py-1">
                @switch($config['style'] ?? 'default')
                    @case('overlay')
                        <div
                            class="relative overflow-hidden rounded-xl shadow-md h-64 transition-transform duration-300 ease-in-out hover:-translate-y-1">
                            <a href="{{ route('post.detail', ['slug' => $post->slug]) }}">
                                @if ($post->media->isNotEmpty())
                                    <img src="{{ asset('/storage/' . $post->media->first()->file_path) }}"
                                        loading="lazy"
                                        alt="{{ $post->media->first()->alt_text ?? $post->title }}"
                                        class="w-full h-full object-cover max-w-[400px]">
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-70 flex flex-col justify-end p-4">
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
                                        loading="lazy"
                                        alt="{{ $post->media->first()->alt_text ?? $post->title }}"
                                        class="w-full h-48 object-cover rounded-xl max-w-[400px]">
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
                                        class="w-full h-48 object-cover max-w-[400px]">
                                @endif
                                <div class="p-4">
                                    <div class="text-base font-semibold mb-2">{{ $post->title }}</div>
                                    <p class="text-gray-600">{{ Str::limit($post->summary, 100, '...') }}</p>
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
            </div>
        @endforeach
    </div>
    @if (isset($config['show_pagination']) && $config['show_pagination'])
        <div class="swiper-pagination"></div>
    @endif
    @if (isset($config['show_navigation']) && $config['show_navigation'])
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let swiperConfig = {
            pagination: {
                el: '.{{ $uniqueId }} .swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.{{ $uniqueId }} .swiper-button-next',
                prevEl: '.{{ $uniqueId }} .swiper-button-prev',
            },
            slidesPerView: 1,
            spaceBetween: @json(($config['space_between'] ?? null !== '' ? $config['space_between'] ?? 20 : 20) ?? 20),
            breakpoints: {
                640: {
                    slidesPerView: {{ $columns['sm'] }},
                },
                768: {
                    slidesPerView: {{ $columns['md'] }},
                },
                1024: {
                    slidesPerView: {{ $columns['lg'] }},
                },
            },
        };

        if (@json(($config['autoplay_speed'] ?? null !== '' ? $config['autoplay_speed'] ?? 0 : 0) ?? 0) >= 1000) {
            swiperConfig.autoplay = {
                delay: @json(($config['autoplay_speed'] ?? null !== '' ? $config['autoplay_speed'] ?? 3000 : 3000) ?? 3000),
                disableOnInteraction: false,
            };
            swiperConfig.loop = true;
        }
        const swiper = new Swiper('.{{ $uniqueId }}', swiperConfig);
    });
</script>
