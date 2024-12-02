<footer class="" style="background-color: {{ $footerConfig['background_color'] ?? '#fff' }}">
    <div class="max-w-7xl mx-auto px-2">
        @if (!empty($footerConfig) && isset($footerConfig['columns']) && !empty($footerConfig['columns']))

            <style>
                .title-with-underline {
                    position: relative;
                    display: inline-block;
                }

                .title-with-underline::after {
                    content: '';
                    position: absolute;
                    left: 0;
                    bottom: -4px;
                    width: 35px;
                    height: 2px;
                    background-color: {{ $footerConfig['title_color'] ?? '#000000' }};
                }
            </style>

            <div class="mx-auto p-4 py-6 lg:py-8">
                <div style="color: {{ $footerConfig['base_color'] ?? '#FFFFFF' }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 lg:gap-8 md:gap-4 gap-2 text-sm gap-y-6 md:gap-y-8">
                    @if(!empty($footerConfig['columns']))
                        @foreach ($footerConfig['columns'] as $column)
                            <div>
                                <h2 class="text-lg font-bold uppercase mb-6 title-with-underline text-[{{ $footerConfig['title_color'] ?? '#000000' }}]">
                                    {{ $column['title'] ?? 'No Title' }}
                                </h2>

                                @if ($column['content_type'] === 'text' && !empty($column['text_content']))
                                    {!! $column['text_content'] !!}
                                @endif

                                @if ($column['content_type'] === 'image' && !is_null($column['image_content']))
                                    <img src="{{ asset('/' . $column['image_content']) }}" 
                                         alt="{{ $column['title'] ?? 'No Title' }}" loading="lazy" class="h-12 w-auto">
                                @endif

                                @if ($column['content_type'] === 'menu' && !is_null($column['menu']))
                                    <ul style="color: {{ $footerConfig['base_color'] ?? '#FFFFFF' }}">
                                        @foreach ($column['menu']['items'] as $menuItem)
                                            <li class="mb-2">
                                                <a href="{{ $menuItem['url'] }}" target="{{ $menuItem['target'] }}"
                                                   class="hover:underline">
                                                    {{ $menuItem['title'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if ($column['content_type'] === 'business' && !is_null($column['business']))
                                    <a href="/">
                                        @php
                                            $logo_size = $footerConfig['logo_size'] ?? '45';
                                        @endphp
                                        <img style="height: {{$logo_size.'px'}}" width="174px"
                                             src="{{ asset('storage/logos/logo.png') }}"
                                             loading="lazy"
                                             alt="Logo">
                                    </a>
                                    <p class="mb-2 text-2xl font-bold">{{ $column['business']['name'] }}</p>
                                    <p class="mb-2"><span class="font-bold">Địa chỉ: </span>{{ $column['business']['address'] }}</p>
                                    <p class="mb-2"><span class="font-bold">Số điện thoại: </span>{{ $column['business']['phone'] }}</p>
                                    <p class="mb-2"><span class="font-bold">Email: </span>{{ $column['business']['email'] }}</p>
                                    <p class="mb-2"><span class="font-bold">Website: </span>{{ $column['business']['website'] }}</p>
                                @endif

                                @if ($column['content_type'] === 'social_media' && !empty($column['social_media']))
                                    <div class="flex space-x-3">
                                        <ul class="text-primary font-medium">
                                            @foreach ($column['social_media'] as $social)
                                                <a target="_blank" href="{{ $social['url'] ?? '/' }}" aria-label="social"
                                                   class="mb-3 bg-black text-white shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2 inline-flex">
                                                    @if(!empty($social['icon']))
                                                        <i class="{{ $social['icon'] }}"></i>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if ($column['content_type'] === 'iframe' && !is_null($column['iframe_content']))
                                    <div class="mx-auto w-full">
                                        {!! $column['iframe_content'] !!}
                                    </div>
                                @endif

                                @if ($column['content_type'] === 'google_map' && !is_null($column['google_map']))
                                    <div class="mx-auto w-full">
                                        {!! $column['google_map'] !!}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>

    @if (!empty($footerConfig) && isset($footerConfig['columns']) && !empty($footerConfig['columns']))
        <hr class="border-gray-200 sm:mx-auto dark:border-gray-700 my-4" />

        <div class="max-w-7xl mx-auto px-2">
            <div style="color: {{ $footerConfig['base_color'] ?? '#FFFFFF' }}"
                class="mx-auto sm:flex sm:items-center sm:justify-between p-4">
               <span class="text-sm sm:text-center">© {{ date('Y') }} All Rights Reserved.</span>
                <div class="flex mt-4 sm:mt-0">
                    @foreach ($footerConfig['columns'] as $column)
                        @if ($column['content_type'] === 'social_media' && !empty($column['social_media']))
                            @foreach ($column['social_media'] as $social)
                                <a target="_blank" href="{{ $social['url'] ?? '/' }}" class="mx-2">
                                    @if(!empty($social['icon']))
                                        <i class="{{ $social['icon'] }}"></i>
                                    @endif
                                </a>
                            @endforeach
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</footer>
