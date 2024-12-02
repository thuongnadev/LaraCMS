@extends('theme::layouts.master')

@section('content')
    @livewire('theme::header')
    @livewire('theme::menu')
    @livewire('theme::breadcrumb', ['slug' => 'kiem-tra-ten-mien', 'name' => 'Kiểm tra tên miền'])
    @livewire('theme::domain-lookup-detail')
    @livewire('theme::footer')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.documentElement.style.setProperty('--swiper-theme-color', @json($primaryColor));

        document.title = 'Kiểm tra tên miền';
    });
</script>
