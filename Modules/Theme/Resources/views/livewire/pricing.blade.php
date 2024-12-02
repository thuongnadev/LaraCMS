<div>
    @if(!empty($config['layout']))
        @if ($config['layout'] == 'hosting_pricing')
            @livewire('theme::hosting-pricing', ['config' => $config])
        @endif

        @if ($config['layout'] == 'domain_pricing')
            @livewire('theme::domain-pricing', ['config' => $config])
        @endif

        @if ($config['layout'] == 'pricing_design')
            @livewire('theme::pricing-design', ['config' => $config])
        @endif
    @endif
</div>