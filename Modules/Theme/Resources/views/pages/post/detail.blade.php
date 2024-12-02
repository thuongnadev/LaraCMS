@extends('theme::layouts.master')

@section('content')
    @livewire('theme::header')
    @livewire('theme::menu')
    @livewire('theme::breadcrumb', ['slug' => $slug, 'name' => $name])
    @livewire('theme::post-detail', ['slug' => $slug])
    @livewire('theme::footer')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.setProperty('--swiper-theme-color', @json($primaryColor));

        var pageName = @json($name);

        if (pageName) {
            document.title = pageName;
        }
    });
</script>
