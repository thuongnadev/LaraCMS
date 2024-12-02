<?php
use Modules\PageMain\Entities\PageMain;

$pageMain = PageMain::where('is_active', true)->first() ?? new PageMain();
$locale = str_replace('_', '-', app()->getLocale());
?>
<!DOCTYPE html>
<html lang="{{ $locale }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ e($pageMain->getTitle() ?? config('app.name', 'Trang chủ')) }}</title>

    <meta name="description"
        content="{{ e($pageMain->getMetaDescription() ?? config('app.description', 'Mô tả mặc định cho trang web')) }}">
    <meta name="keywords"
        content="{{ e($pageMain->meta_keywords ?? config('app.keywords', 'từ khóa mặc định, website')) }}">
    <link rel="canonical" href="{{ e($pageMain->getCanonicalUrl() ?? url()->current()) }}">
    <meta name="robots" content="{{ e($pageMain->robots ?? 'index, follow') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ e($pageMain->og_type ?? 'website') }}">
    <meta property="og:url" content="{{ e($pageMain->getCanonicalUrl() ?? url()->current()) }}">
    <meta property="og:title" content="{{ e($pageMain->getOgTitle() ?? config('app.name', 'Tiêu đề mặc định')) }}">
    <meta property="og:description"
        content="{{ e($pageMain->getOgDescription() ?? config('app.description', 'Mô tả mặc định cho Open Graph')) }}">
    <meta property="og:image"
        content="{{ e($pageMain->og_image ? asset('storage/'. $pageMain->og_image) : asset('/img-og.png')) }}">
    <meta property="og:locale" content="{{ e($pageMain->og_locale ?? $locale) }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="{{ e($pageMain->twitter_card ?? 'summary_large_image') }}">
    <meta name="twitter:url" content="{{ e($pageMain->getCanonicalUrl() ?? url()->current()) }}">
    <meta name="twitter:title"
        content="{{ e($pageMain->getTwitterTitle() ?? config('app.name', 'Tiêu đề Twitter mặc định')) }}">
    <meta name="twitter:description"
        content="{{ e($pageMain->getTwitterDescription() ?? config('app.description', 'Mô tả Twitter mặc định')) }}">
    <meta name="twitter:image"
        content="{{ e($pageMain->twitter_image ? asset('storage/' . $pageMain->twitter_image) : ($pageMain->og_image ? asset(Storage::url($pageMain->og_image)) : asset('/img-twitter.png'))) }}">
    @if ($pageMain->twitter_site)
        <meta name="twitter:site" content="{{ e($pageMain->twitter_site) }}">
    @endif
    @if ($pageMain->twitter_creator)
        <meta name="twitter:creator" content="{{ e($pageMain->twitter_creator) }}">
    @endif

    @if ($pageMain->author)
        <meta name="author" content="{{ e($pageMain->author) }}">
    @endif

    @if ($pageMain->published_time)
        <meta property="article:published_time" content="{{ $pageMain->published_time->toIso8601String() }}">
    @endif

    @if ($pageMain->modified_time)
        <meta property="article:modified_time" content="{{ $pageMain->modified_time->toIso8601String() }}">
    @endif

    <!-- JSON-LD for Schema.org -->
    <script type="application/ld+json">
        {!! json_encode($pageMain->getJsonLd() ?? [
            "@context" => "https://schema.org",
            "@type" => "WebPage",
            "name" => config('app.name', 'Trang mặc định'),
            "description" => config('app.description', 'Mô tả mặc định cho trang web')
        ]) !!}
    </script>

    @vite(['Resources/assets/sass/app.scss', 'Resources/assets/js/app.js'], 'build-theme')
    <link rel="shortcut icon" href="{{ asset('storage/favicons/favicon.png') }}" type="image/x-icon">

    @if ($pageMain->google_analytics_id)
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ e($pageMain->google_analytics_id) }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ e($pageMain->google_analytics_id) }}');
        </script>
    @endif

    @livewireStyles
</head>

<body>
    @yield('content')
    @livewireScripts
</body>

</html>
