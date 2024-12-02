<div>
    @isset($config['layout'])
        @if ($config['layout'] == 'slide')
            @livewire('theme::swiper-slide', ['config' => $config])
        @elseif ($config['layout'] == 'grid')
            @livewire('theme::post-grid', ['config' => $config])
        @endif
    @endisset
</div>
