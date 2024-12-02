<?php

if (!isset($config['image']) || empty($config['image'])) {
    $config['image'] = [];
} else {
    $imageArray = !empty($config['image']) ? (is_string($config['image']) ? explode(',', $config['image']) : $config['image']) : [];
    $imageArray = (array) $imageArray;

    $splitPipeSeparated = function ($value) {
        return is_string($value) ? array_map('trim', explode('|', $value)) : $value;
    };

    $titles = $splitPipeSeparated($config['title'] ?? '');
    $descriptions = $splitPipeSeparated($config['description'] ?? '');
    $ctaTexts = $splitPipeSeparated($config['cta_text'] ?? '');
    $ctaLinks = $splitPipeSeparated($config['cta_link'] ?? '');

    $config['image'] = array_map(
        function ($image, $index) use ($titles, $descriptions, $ctaTexts, $ctaLinks) {
            $processedImage = $image;
            if (!empty($image)) {
                if (filter_var($image, FILTER_VALIDATE_URL)) {
                    $processedImage = $image;
                } else {
                    $processedImage = asset('storage/' . ltrim($image, '/'));
                }
            }

            if (!empty($processedImage)) {
                return [
                    'image' => $processedImage,
                    'title' => $titles[$index] ?? '',
                    'description' => $descriptions[$index] ?? '',
                    'cta_text' => $ctaTexts[$index] ?? '',
                    'cta_link' => $ctaLinks[$index] ?? '',
                ];
            }
            return null;
        },
        $imageArray,
        array_keys($imageArray),
    );

    $config['image'] = array_filter($config['image']);
}
?>

<div class="swiper {{ $uniqueId }}">
    <div class="swiper-wrapper">
        @foreach ($config['image'] as $banner)
            @if (!empty($banner['image']))
                <div class="swiper-slide relative">
                    <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] ?? '' }}" loading="lazy" width="978"
                        height="377" class="w-full h-full object-cover">
                    @if (
                        (isset($banner['title']) && $banner['title']) ||
                            (isset($banner['description']) && $banner['description']) ||
                            (isset($banner['cta_text'], $banner['cta_link']) && $banner['cta_text'] && $banner['cta_link']))
                        @php
                            $alignment = $config['content_alignment'] ?? 'center';
                            $alignmentClasses = [
                                'left' => 'items-start text-left',
                                'center' => 'items-center text-center',
                                'right' => 'items-end text-right',
                            ][$alignment];
                        @endphp
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center {{ $alignmentClasses }}">
                            <div class="w-full">
                                <div class="max-w-7xl mx-auto px-2">
                                    @if ($banner['title'])
                                    <div
                                        class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-white mb-2 md:mb-4 banner-title">
                                        {{ $banner['title'] ?? '' }}</div>
                                @endif
                                @if ($banner['description'] ?? '')
                                    <p
                                        class="text-sm sm:text-base md:text-lg lg:text-xl text-white mb-4 md:mb-8 banner-description">
                                        {{ $banner['description'] ?? '' }}</p>
                                @endif
                                @if ($banner['cta_text'] && $banner['cta_link'])
                                    <a href="{{ $banner['cta_link'] }}">
                                        <div class="button-main">
                                            <button type="submit" class="cssbuttons-io-button">
                                                <span>{{ $banner['cta_text'] }}</span>
                                                <div class="icon">
                                                    <svg height="24" width="24" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M0 0h24v24H0z" fill="none"></path>
                                                        <path
                                                            d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </a>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @if (isset($config['show_pagination']) && $config['show_pagination'])
        <div class="swiper-pagination"></div>
    @endif
    @if (isset($config['show_navigation']) && $config['show_navigation'])
        <style>
            .swiper-button-next::after,
            .swiper-button-prev::after {
                font-size: 2.4rem !important;
            }

            @media (max-width: 768px) {

                .swiper-button-next::after,
                .swiper-button-prev::after {
                    font-size: 1.2rem !important;
                }
            }
        </style>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.setProperty('--swiper-theme-color', @json($primaryColor ?? ''));

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
        };

        if (@json(($config['autoplay_speed'] ?? null !== '' ? $config['autoplay_speed'] ?? 0 : 0) ?? 0) >
            1000
        ) {
            swiperConfig.autoplay = {
                delay: @json(($config['autoplay_speed'] ?? null !== '' ? $config['autoplay_speed'] ?? 3000 : 3000) ?? 3000),
                disableOnInteraction: false,
            };
            swiperConfig.loop = true;
        }

        const swiper = new Swiper('.{{ $uniqueId }}', swiperConfig);
    });
</script>
