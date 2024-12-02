@php
    $isBanner = $componentName === 'swiper-banner';
    $hasHeading =
        !empty($config['heading']) ||
        !empty($config['heading_first_part']) ||
        !empty($config['heading_second_part']) ||
        !empty($config['heading_sub']);
@endphp

<div class="bg-no-repeat bg-cover bg-center {{ !$isBanner ? 'py-2 md:py-8' : '' }}"
    style="
    @if (!empty($config['background_image'])) background-image: url('/storage/{{ $config['background_image'] ?? '' }}'); @endif
    @if (!empty($config['background_color'])) background-color: {{ $config['background_color'] ?? 'transparent' }}; @endif
  ">
    <div class="{{ $isBanner ? 'w-full' : 'max-w-7xl  mx-auto px-2' }}">
        @if ($config['heading'] || $config['heading_first_part'] || $config['heading_second_part'] || $config['heading_sub'])
            <div class="flex flex-col items-center my-5">
                <div class="text-xl md:text-3xl font-bold animated-text text-center">
                    @if ($config['heading'] ?? '')
                        <span
                            style="color: {{ $config['heading_color'] ? $config['heading_color'] : $primaryColor }}">{{ $config['heading'] ?? '' }}</span>
                    @else
                        <span
                            style="color: {{ $config['first_part_color'] ? $config['first_part_color'] : $primaryColor }}">{{ $config['heading_first_part'] ?? '' }}</span>
                        <span
                            style="color: {{ $config['second_part_color'] ? $config['second_part_color'] : $primaryColor }}">{{ $config['heading_second_part'] ?? '' }}</span>
                    @endif
                </div>
                @if ($config['heading_sub'] ?? '')
                    <div class="text-sm md:text-base animated-text max-w-6xl text-center mt-2">
                        <span
                            style="color: {{ $config['heading_sub_color'] ?? 'text-black' }}">{{ $config['heading_sub'] ?? '' }}</span>
                    </div>
                @endif
            </div>
        @endif
        <div
            class="{{ $config['heading'] ?? ('' || $config['heading_first_part'] ?? ('' || $config['heading_second_part'] ?? ('' || $config['heading_sub'] ?? ''))) ? 'mt-10' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
