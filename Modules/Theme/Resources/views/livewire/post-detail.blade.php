<div class="max-w-7xl mx-auto px-4 py-8">
    @if ($post)
        <div id="seo-data" data-seo-title="{{ $post->seo_title }}" data-seo-description="{{ $post->seo_description }}"
            data-seo-keyword="{{ $post->seo_keywords }}">
        </div>

        <article class="bg-white border-[1px] rounded-xl overflow-hidden">
            <h1 class="text-3xl font-bold text-gray-900 p-6 text-center">{{ $post->title }}</h1>

            <div class="p-6">
                <div class="flex flex-wrap items-center text-sm text-gray-600 mb-4">
                    <span class="mr-4">
                        <i class="far fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($post->created_at)->format('d/m/Y H:i') }}
                    </span>
                </div>

                <div class="post-summary text-gray-700 mb-6">
                    {!! $post->summary !!}
                </div>

                <div class="toc-wrap">
                    <div class="toc-title">Mục lục</div>
                    <div id="toc"></div>
                </div>

                <div class="post-content prose max-w-none">
                    {!! $post->content !!}
                </div>

                <div class="flex">

                </div>
                <div>
                    @if ($post->tags->isNotEmpty())
                        <div class="mt-6 pt-2 border-t border-gray-200">
                            <span class="text-sm font-semibold text-gray-700 mr-2">Thẻ:</span>
                            @foreach ($post->tags as $tag)
                                <spab class="inline-block rounded-full px-3 py-1 text-sm font-semibold mr-2 mb-2"
                                    style="color: {{ $primaryColor }};">
                                    {{ $tag->name }}
                                </spab>
                            @endforeach
                        </div>
                    @endif

                    @if ($post->categories->isNotEmpty())
                        <div class="pt-2">
                            <span class="text-sm font-semibold text-gray-700 mr-2">Danh mục:</span>
                            @foreach ($post->categories as $category)
                                <spab class="inline-block rounded-full px-3 py-1 text-sm font-semibold mr-2 mb-2"
                                    style="color: {{ $primaryColor }};">
                                    {{ $category->name }}
                                </spab>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex space-x-2 mt-5">
                    <div
                        class="flex items-center justify-center me-4 text-sm font-semibold text-gray-900 border border-gray-300 rounded-md p-2 bg-white shadow-sm">
                        <i class="fas fa-share-alt me-2"></i>
                        Chia sẻ:
                    </div>
                    {{-- facebook --}}
                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 93 92"
                                fill="none">
                                <rect x="1.13867" width="91.5618" height="91.5618" rx="15" fill="#337FFF" />
                                <path
                                    d="M57.4233 48.6403L58.7279 40.3588H50.6917V34.9759C50.6917 32.7114 51.8137 30.4987 55.4013 30.4987H59.1063V23.4465C56.9486 23.1028 54.7685 22.9168 52.5834 22.8901C45.9692 22.8901 41.651 26.8626 41.651 34.0442V40.3588H34.3193V48.6403H41.651V68.671H50.6917V48.6403H57.4233Z"
                                    fill="white" />
                            </svg>
                        </a>
                        <span
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            Chia sẻ Facebook
                            <span
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                        </span>
                    </button>

                    {{-- X --}}
                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ $title }}"
                            target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 93 92"
                                fill="none">
                                <rect x="0.138672" width="91.5618" height="91.5618" rx="15" fill="black" />
                                <path
                                    d="M50.7568 42.1716L69.3704 21H64.9596L48.7974 39.383L35.8887 21H21L40.5205 48.7983L21 71H25.4111L42.4788 51.5869L56.1113 71H71L50.7557 42.1716H50.7568ZM44.7152 49.0433L42.7374 46.2752L27.0005 24.2492H33.7756L46.4755 42.0249L48.4533 44.7929L64.9617 67.8986H58.1865L44.7152 49.0443V49.0433Z"
                                    fill="white" />
                            </svg>
                        </a>
                        <span
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            Chia sẻ Twitter
                            <span
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                        </span>
                    </button>

                    {{-- Mail --}}
                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                        <a href="mailto:?subject={{ $title }}&body={{ urlencode($url) }}" target="_blank">
                            <svg width="48" height="48" viewBox="0 0 92 92" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="0.638672" y="0.5" width="90.5618" height="90.5618" rx="14.5"
                                    fill="white" stroke="#C4CFE3" />
                                <path
                                    d="M22.0065 66.1236H30.4893V45.5227L18.3711 36.4341V62.4881C18.3711 64.4997 20.001 66.1236 22.0065 66.1236Z"
                                    fill="#4285F4" />
                                <path
                                    d="M59.5732 66.1236H68.056C70.0676 66.1236 71.6914 64.4937 71.6914 62.4881V36.4341L59.5732 45.5227"
                                    fill="#34A853" />
                                <path
                                    d="M59.5732 29.7693V45.5229L71.6914 36.4343V31.587C71.6914 27.0912 66.5594 24.5282 62.9663 27.2245"
                                    fill="#FBBC04" />
                                <path d="M30.4893 45.5227V29.769L45.0311 40.6754L59.5729 29.769V45.5227L45.0311 56.429"
                                    fill="#EA4335" />
                                <path
                                    d="M18.3711 31.587V36.4343L30.4893 45.5229V29.7693L27.0962 27.2245C23.4971 24.5282 18.3711 27.0912 18.3711 31.587Z"
                                    fill="#C5221F" />
                            </svg>
                        </a>
                        <span
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            Gửi Email
                            <span
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                        </span>
                    </button>

                    {{-- telegram --}}
                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                        <a href="https://t.me/share/url?url={{ urlencode($url) }}&text={{ $title }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 92 93"
                                fill="none">
                                <rect x="0.138672" y="1" width="91.5618" height="91.5618" rx="15"
                                    fill="#34AADF" />
                                <path
                                    d="M25.0881 43.5652C25.0881 43.5652 43.716 35.7194 50.1765 32.9567C52.6532 31.8518 61.0518 28.3155 61.0518 28.3155C61.0518 28.3155 64.9282 26.7685 64.6052 30.5256C64.4974 32.0728 63.6361 37.4874 62.7747 43.3442C61.4825 51.6322 60.0827 60.6935 60.0827 60.6935C60.0827 60.6935 59.8674 63.2352 58.0369 63.6772C56.2065 64.1192 53.1914 62.1302 52.6532 61.6881C52.2223 61.3566 44.5774 56.3838 41.7778 53.9527C41.0241 53.2897 40.1627 51.9637 41.8854 50.4166C45.7618 46.7699 50.3919 42.2392 53.1914 39.3661C54.4836 38.04 55.7757 34.9459 50.3919 38.703C42.7469 44.1178 35.2096 49.201 35.2096 49.201C35.2096 49.201 33.4868 50.306 30.2565 49.3115C27.0261 48.317 23.2575 46.9909 23.2575 46.9909C23.2575 46.9909 20.6734 45.3334 25.0881 43.5652Z"
                                    fill="white" />
                            </svg>
                        </a>
                        <span
                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                            Chia sẻ Telegram
                            <span
                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                        </span>
                    </button>
                </div>
            </div>
        </article>

        @if ($relatedPosts->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-xl font-semibold mb-6 text-gray-900">Bài viết liên quan</h2>
                <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($relatedPosts as $relatedPost)
                        @switch($style)
                            @case('overlay')
                                <div
                                    class="relative overflow-hidden rounded-xl shadow-md h-64 transition-transform duration-300 ease-in-out hover:-translate-y-1">
                                    <a href="{{ route('post.detail', $relatedPost->slug) }}">
                                        @if ($relatedPost->media->isNotEmpty())
                                            <img src="{{ asset('/storage/' . $relatedPost->media->first()->file_path) }}"
                                                loading="lazy"
                                                alt="{{ $relatedPost->media->first()->alt_text ?? $relatedPost->title }}"
                                                class="w-full h-full object-cover">
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-end p-4">
                                            <div class="text-lg font-semibold mb-2 text-white">{{ $relatedPost->title }}</div>
                                            <p class="text-sm text-gray-300">{{ Str::limit($relatedPost->summary, 100) }}</p>
                                            @if ($relatedPost->tags->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->tags as $tag)
                                                        @if (!empty(trim($tag->name)))
                                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                                style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($relatedPost->categories->isNotEmpty())
                                                <div class="mt-1 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->categories as $category)
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
                                <div
                                    class="bg-white shadow-md rounded-xl overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1">
                                    <a href="{{ route('post.detail', $relatedPost->slug) }}">
                                        @if ($relatedPost->media->isNotEmpty())
                                            <img src="{{ asset('/storage/' . $relatedPost->media->first()->file_path) }}"
                                                alt="{{ $relatedPost->media->first()->alt_text ?? $relatedPost->title }}"
                                                loading="lazy" class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <div class="text-lg font-semibold mb-2">{{ $relatedPost->title }}</div>
                                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($relatedPost->summary, 100) }}
                                            </p>
                                            @if ($relatedPost->tags->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->tags as $tag)
                                                        @if (!empty(trim($tag->name)))
                                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                                style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($relatedPost->categories->isNotEmpty())
                                                <div class="mt-1 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->categories as $category)
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

                            @case('minimal')
                                <div
                                    class="border-b border-gray-200 pb-4 transition-transform duration-300 ease-in-out hover:-translate-y-1">
                                    <a href="{{ route('post.detail', $relatedPost->slug) }}">
                                        <div class="text-lg font-semibold mb-2">{{ $relatedPost->title }}</div>
                                        <p class="text-sm text-gray-600 mb-4">{{ Str::limit($relatedPost->summary, 100) }}</p>
                                        @if ($relatedPost->tags->isNotEmpty())
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach ($relatedPost->tags as $tag)
                                                    @if (!empty(trim($tag->name)))
                                                        <span class="text-xs text-white px-2 py-1 rounded"
                                                            style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                            {{ $tag->name }}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                        @if ($relatedPost->categories->isNotEmpty())
                                            <div class="mt-1 flex flex-wrap gap-2">
                                                @foreach ($relatedPost->categories as $category)
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
                                    class="bg-white shadow-md rounded-xl overflow-hidden transition-transform duration-300 ease-in-out hover:-translate-y-1">
                                    <a href="{{ route('post.detail', $relatedPost->slug) }}">
                                        @if ($relatedPost->media->isNotEmpty())
                                            <img src="{{ asset('/storage/' . $relatedPost->media->first()->file_path) }}"
                                                loading="lazy"
                                                alt="{{ $relatedPost->media->first()->alt_text ?? $relatedPost->title }}"
                                                class="w-full h-48 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <div class="text-lg font-semibold mb-2">{{ $relatedPost->title }}</div>
                                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($relatedPost->summary, 100) }}
                                            </p>
                                            @if ($relatedPost->tags->isNotEmpty())
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->tags as $tag)
                                                        @if (!empty(trim($tag->name)))
                                                            <span class="text-xs text-white px-2 py-1 rounded"
                                                                style="{{ !empty(trim($tag->name)) ? 'background-color: ' . $this->primaryColor : '' }}">
                                                                {{ $tag->name }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if ($relatedPost->categories->isNotEmpty())
                                                <div class="mt-1 flex flex-wrap gap-2">
                                                    @foreach ($relatedPost->categories as $category)
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
            </div>
        @endif
    @else
        <p class="text-center text-gray-600">Không tìm thấy bài viết.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const figures = document.querySelectorAll('figure');
        figures.forEach(function(figure) {
            var img = figure.querySelector('img');
            var figcaption = figure.querySelector('figcaption');

            if (img && figcaption) {
                img.setAttribute('alt', figcaption.textContent.trim());
            }
        });

        const headings = document.querySelectorAll(
            '.post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6'
        );
        const tocContainer = document.querySelector('#toc');
        const tocWrap = document.querySelector('.toc-wrap');

        if (headings.length === 0 || !tocContainer || !tocWrap) {
            console.log('No headings found or TOC elements missing, hiding TOC');
            if (tocWrap) {
                tocWrap.style.display = 'none';
            }
            return;
        }

        const startingLevel = headings[0].tagName[1];
        const toc = document.createElement('ul');
        const prevLevels = [0, 0, 0, 0, 0, 0];

        for (let i = 0; i < headings.length; i++) {
            const heading = headings[i];
            const level = parseInt(heading.tagName[1]);

            prevLevels[level - 1]++;
            for (let j = level; j < prevLevels.length; j++) {
                prevLevels[j] = 0;
            }

            const sectionNumber = prevLevels.slice(startingLevel - 1, level).join('.').replace(/\.0/g, "");

            const newHeadingId = `${heading.textContent.toLowerCase().replace(/ /g, '-')}`;
            heading.id = newHeadingId;

            const anchor = document.createElement('a');
            anchor.setAttribute('href', `#${newHeadingId}`);
            anchor.textContent = heading.textContent;

            anchor.addEventListener('click', (event) => {
                event.preventDefault();
                const targetId = event.target.getAttribute('href').slice(1);
                const targetElement = document.getElementById(targetId);

                const offset = 70;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });

                history.pushState(null, null, `#${targetId}`);
            });

            const listItem = document.createElement('li');
            listItem.textContent = sectionNumber + ' ';
            listItem.appendChild(anchor);

            const className = `toc-${heading.tagName.toLowerCase()}`;
            listItem.classList.add('toc-item');
            listItem.classList.add(className);

            toc.appendChild(listItem);
        }

        tocContainer.innerHTML = '';
        tocContainer.appendChild(toc);
    });
</script>
