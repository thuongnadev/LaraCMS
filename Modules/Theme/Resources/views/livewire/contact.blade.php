<div class="mx-auto max-w-screen-xl px-4 md:px-8">
    @if ($iframeCode)
        <div id="map" class="relative h-[300px] overflow-hidden bg-cover bg-[50%] bg-no-repeat rounded-xl">
            {!! $iframeCode !!}
        </div>
    @endif

    <div
        class="block rounded-lg px-6 py-12 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] md:py-16 md:px-12 mt-1 backdrop-blur-[30px] border border-gray-300">
        <div class="flex md:flex-row flex-col items-center space-x-3">
            <div class="w-full lg:w-6/12 px-3">
                @if ($form)
                    @livewire('theme::form-contact', ['form' => $form])
                @endif
            </div>

            <div class="w-full lg:w-6/12 px-3">
                <div class="grid grid-cols-1 gap-6 mt-6">
                    @if ($contactGroup)
                        @foreach ($contactGroup as $item)
                            <div class="mb-12">
                                <div class="flex items-start">
                                    <div class="shrink-0">
                                        <div style="background-color: rgb({{ $primaryColorRgb }});"
                                            class="inline-block rounded-md p-4 text-white">
                                            @if(!empty($item['icon']))
                                                <x-dynamic-component :component="$item['icon']" class="w-6 h-6 text-white" />
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-2 sm:ml-6 grow">
                                        <p class="mb-2 font-bold">{{ $item['name'] }}</p>
                                        <p class="text-sm text-neutral-500">{{ $item['value'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($images)
        <div class="mt-14">
            <p class="text-center text-md text-gray-700 font-semibold">Được tin tưởng bởi các công ty tốt nhất</p>
            <div class="swiper-logo swiper-container w-full overflow-x-hidden mt-8">
                <div class="swiper-wrapper">
                    @foreach ($images as $image)
                        <div class="swiper-slide flex justify-center items-center w-full">
                            <div class="flex justify-center">
                                <img class="h-40 object-contain border p-3 rounded-xl" style="aspect-ratio: 3/3;" 
                                    src="{{ asset('/storage/' . $image) }}" alt="" loading="lazy">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.querySelector('.swiper-logo')) {
            const swiper = new Swiper('.swiper-logo', {
                slidesPerView: 4,
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    280: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    }
                }
            });
        }
    });
</script>
