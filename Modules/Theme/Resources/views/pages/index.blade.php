@extends('theme::layouts.master')

@section('content')
    @livewire('theme::header')
    @livewire('theme::menu')
    @livewire('theme::breadcrumb')
    @foreach ($configuration as $config)
        @php
            $componentName = $config['component']['name'];
        @endphp
        <x-theme::layout :loop="$loop" :component_name="$componentName" :config="$config['layout']" :primaryColor="$primaryColor">
            <div id="seo-data" data-seo-title="{{ $config['layout']['seo_title'] ?? '' }}"
                data-seo-description="{{ $config['layout']['seo_description'] ?? '' }}"
                data-seo-keyword="{{ $config['layout']['seo_keywords'] ?? '' }}">
            </div>
            @isset($config['component'])
                @livewire('theme::' . $config['component']['name'], ['config' => $config])
            @endisset
        </x-theme::layout>
    @endforeach
    @livewire('theme::footer')
    @livewire('theme::contact-link')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.documentElement.style.setProperty('--swiper-theme-color', @json($primaryColor));
    });
</script>
