<div>
    <div class="flex flex-wrap justify-center items-start gap-y-3 relative">
        @if(!empty($steps))
            @foreach ($steps as $index => $step)
                <div class="flex flex-col items-center text-center w-1/2 md:w-1/3 lg:w-1/12 relative step-item">
                    <div
                        class="w-20 h-20 flex items-center justify-center mb-2 z-20 step-icon">
                        @php
                            $icon = $step['icon'] ?? 'img-default.jpg';
                        @endphp

                        <img src="{{ asset('/storage/' . $icon )}}" alt="{{ $step['name'] ?? 'Icon' }}" loading="lazy" width="80"
                            height="80" >

                    </div>
                    <div class="text-sm text-center w-32 text-white">{{ $index + 1 }}. {{ $step['name'] ?? '' }}</div>
                </div>
                @if ($index < count($steps) - 1)
                    <div class="hidden lg:flex items-center justify-center mt-8">
                        <img src="https://webvps.vn/plugins/images/muiten.png" alt="Arrow" loading="lazy"
                            width="145" 
                            height="22"
                            class="{{ $index % 2 != 0 ? 'custom-arrow' : '' }} transition-transform duration-300">
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
