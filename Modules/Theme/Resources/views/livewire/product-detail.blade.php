<div>
    <div
        style="
    background-image: url({{ asset('bg-product-detail.jpg') }});
    background-position: right center;
    background-repeat: no-repeat;
    background-size: contain;
            ">
        <div class="max-w-7xl mx-auto px-2 py-2 lg:py-8 overflow-x-hidden" x-data="productDetail()">
            <div class="mb-12">
                <div>
                    <div>
                        <div class="uppercase tracking-wide text-sm text-center font-semibold">
                            {{ $product->categories->pluck('name')->implode(', ') }}</div>
                        <h1 class="my-2 text-3xl text-center leading-8 font-extrabold tracking-tight sm:text-3xl"
                            style="color: {{ $this->primaryColor }};">
                            {{ $product->name }}</h1>
                        <div class="grid grid-cols-1 lg:grid-cols-3 mt-5">
                            <div class="lg:col-span-2">
                                @if ($product->productImage->isNotEmpty())
                                    <div class="laptop-mockup cursor-pointer"
                                        x-on:click="openImageModal('{{ asset('/storage/' . $product->productImage->first()->file_path) }}')">
                                        <img src="{{ asset('img-laptop.png') }}" alt="Laptop Frame"
                                            class="laptop-frame">
                                        <div class="product-screen">
                                            <img src="{{ asset('/storage/' . $product->productImage->first()->file_path) }}"
                                                alt="{{ $product->productImage->first()->alt_text ?? $product->name }}"
                                                class="product-image">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="w-full lg:col-span-1 mt-5">
                                <div class="rounded-xl p-4"
                                    style="background-color: rgba({{ $this->hexToRgb($this->primaryColor) }}, 0.2); border: 1px solid {{ $this->primaryColor }};">
                                    <h3 class="text-lg font-bold mb-2">Thông tin liên hệ</h3>
                                    @foreach ($contacts as $contact)
                                        <span
                                            class="flex gap-2 items-center text-sm lg:text-base text-gray-900 mr-3 mb-1">
                                            @php
                                                $icon = '';
                                                $lowercaseName = strtolower($contact['name']);
                                                if (strpos($lowercaseName, 'email') !== false) {
                                                    $icon = 'fas fa-envelope';
                                                } elseif (
                                                    strpos($lowercaseName, 'phone') !== false ||
                                                    strpos($lowercaseName, 'điện thoại') !== false ||
                                                    strpos($lowercaseName, 'hotline') !== false
                                                ) {
                                                    $icon = 'fas fa-phone';
                                                } elseif (
                                                    strpos($lowercaseName, 'address') !== false ||
                                                    strpos($lowercaseName, 'địa chỉ') !== false
                                                ) {
                                                    $icon = 'fas fa-map-marker-alt';
                                                } elseif (strpos($lowercaseName, 'zalo') !== false) {
                                                    $icon = 'fab fa-facebook-messenger';
                                                } elseif (strpos($lowercaseName, 'facebook') !== false) {
                                                    $icon = 'fab fa-facebook-f';
                                                } elseif (strpos($lowercaseName, 'twitter') !== false) {
                                                    $icon = 'fab fa-twitter';
                                                } elseif (strpos($lowercaseName, 'instagram') !== false) {
                                                    $icon = 'fab fa-instagram';
                                                }
                                            @endphp
                                            @if ($icon)
                                                <i class="{{ $icon }} mr-1"
                                                    style="color: {{ $this->primaryColor }};"></i>
                                            @endif
                                            {{ $contact['name'] }}:
                                            <span
                                                class="text-sm lg:text-base font-semibold">{{ $contact['value'] }}</span>
                                        </span>
                                    @endforeach
                                </div>

                                <div class="space-x-2 mt-5">
                                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}"
                                            target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 93 92" fill="none">
                                                <rect x="1.13867" width="91.5618" height="91.5618" rx="15"
                                                    fill="#337FFF" />
                                                <path
                                                    d="M57.4233 48.6403L58.7279 40.3588H50.6917V34.9759C50.6917 32.7114 51.8137 30.4987 55.4013 30.4987H59.1063V23.4465C56.9486 23.1028 54.7685 22.9168 52.5834 22.8901C45.9692 22.8901 41.651 26.8626 41.651 34.0442V40.3588H34.3193V48.6403H41.651V68.671H50.6917V48.6403H57.4233Z"
                                                    fill="white" />
                                            </svg>
                                        </a>
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                            Chia sẻ Facebook
                                            <span
                                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                                        </span>
                                    </button>

                                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}&text={{ $title }}"
                                            target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 93 92" fill="none">
                                                <rect x="0.138672" width="91.5618" height="91.5618" rx="15"
                                                    fill="black" />
                                                <path
                                                    d="M50.7568 42.1716L69.3704 21H64.9596L48.7974 39.383L35.8887 21H21L40.5205 48.7983L21 71H25.4111L42.4788 51.5869L56.1113 71H71L50.7557 42.1716H50.7568ZM44.7152 49.0433L42.7374 46.2752L27.0005 24.2492H33.7756L46.4755 42.0249L48.4533 44.7929L64.9617 67.8986H58.1865L44.7152 49.0443V49.0433Z"
                                                    fill="white" />
                                            </svg>
                                        </a>
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                            Chia sẻ Twitter
                                            <span
                                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                                        </span>
                                    </button>

                                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                                        <a href="mailto:?subject={{ $title }}&body={{ urlencode($url) }}"
                                            target="_blank">
                                            <svg width="48" height="48" viewBox="0 0 92 92" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0.638672" y="0.5" width="90.5618" height="90.5618"
                                                    rx="14.5" fill="white" stroke="#C4CFE3" />
                                                <path
                                                    d="M22.0065 66.1236H30.4893V45.5227L18.3711 36.4341V62.4881C18.3711 64.4997 20.001 66.1236 22.0065 66.1236Z"
                                                    fill="#4285F4" />
                                                <path
                                                    d="M59.5732 66.1236H68.056C70.0676 66.1236 71.6914 64.4937 71.6914 62.4881V36.4341L59.5732 45.5227"
                                                    fill="#34A853" />
                                                <path
                                                    d="M59.5732 29.7693V45.5229L71.6914 36.4343V31.587C71.6914 27.0912 66.5594 24.5282 62.9663 27.2245"
                                                    fill="#FBBC04" />
                                                <path
                                                    d="M30.4893 45.5227V29.769L45.0311 40.6754L59.5729 29.769V45.5227L45.0311 56.429"
                                                    fill="#EA4335" />
                                                <path
                                                    d="M18.3711 31.587V36.4343L30.4893 45.5229V29.7693L27.0962 27.2245C23.4971 24.5282 18.3711 27.0912 18.3711 31.587Z"
                                                    fill="#C5221F" />
                                            </svg>
                                        </a>
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                            Gửi Email
                                            <span
                                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                                        </span>
                                    </button>

                                    <button class="relative group transition-all duration-500 hover:-translate-y-2">
                                        <a
                                            href="https://t.me/share/url?url={{ urlencode($url) }}&text={{ $title }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 92 93" fill="none">
                                                <rect x="0.138672" y="1" width="91.5618" height="91.5618" rx="15"
                                                    fill="#34AADF" />
                                                <path
                                                    d="M25.0881 43.5652C25.0881 43.5652 43.716 35.7194 50.1765 32.9567C52.6532 31.8518 61.0518 28.3155 61.0518 28.3155C61.0518 28.3155 64.9282 26.7685 64.6052 30.5256C64.4974 32.0728 63.6361 37.4874 62.7747 43.3442C61.4825 51.6322 60.0827 60.6935 60.0827 60.6935C60.0827 60.6935 59.8674 63.2352 58.0369 63.6772C56.2065 64.1192 53.1914 62.1302 52.6532 61.6881C52.2223 61.3566 44.5774 56.3838 41.7778 53.9527C41.0241 53.2897 40.1627 51.9637 41.8854 50.4166C45.7618 46.7699 50.3919 42.2392 53.1914 39.3661C54.4836 38.04 55.7757 34.9459 50.3919 38.703C42.7469 44.1178 35.2096 49.201 35.2096 49.201C35.2096 49.201 33.4868 50.306 30.2565 49.3115C27.0261 48.317 23.2575 46.9909 23.2575 46.9909C23.2575 46.9909 20.6734 45.3334 25.0881 43.5652Z"
                                                    fill="white" />
                                            </svg>
                                        </a>
                                        <span
                                            class="absolute left-1/2 -translate-x-1/2 bottom-full mb-2 w-auto px-2 py-1 text-sm text-white bg-black rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
                                            Chia sẻ Telegram
                                            <span
                                                class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-full w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-black"></span>
                                        </span>
                                    </button>
                                </div>


                                <div class="button-main mt-5" x-on:click="openModal('{{ $product->name }}')">
                                    <button class="cssbuttons-io-button">
                                        Tư vấn mẫu này
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="isImageModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="closeImageModal()" x-cloak
                class="fixed inset-0 z-50 bg-white bg-opacity-80 transition-opacity overflow-hidden"
                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="h-full pt-4 px-4 pb-20 text-center block p-0 overflow-y-auto">
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <img :src="selectedImage" alt="Product Image" class="w-full h-auto">
                    </div>
                </div>

                <div class="button-close fixed z-50 top-10 right-10">
                    <button type="button" x-on:click="closeImageModal()">
                        <span class="text">Đóng</span>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path
                                    d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                                </path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="closeModal()" x-cloak
                class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-white bg-opacity-30 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    @isset($form)
                        @livewire('theme::form', ['form' => $form, 'name' => $selectedProductName])
                    @endisset
                </div>
                <div class="button-close fixed z-50 top-10 right-10">
                    <button type="button" x-on:click="closeModal()">
                        <span class="text">Đóng</span>
                        <span class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path
                                    d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                                </path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

            <style>
                .laptop-mockup {
                    position: relative;
                    width: 100%;
                    padding-top: 56.25%;
                }

                .laptop-frame {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                    z-index: 2;
                }

                .product-screen {
                    position: absolute;
                    top: 8%;
                    left: 12.3%;
                    width: 75.5%;
                    height: 83%;
                    overflow: hidden;
                    z-index: 1;
                }

                .product-image {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    object-fit: cover;
                }
            </style>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Sản phẩm liên quan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($relatedProducts as $product)
                <div class="relative overflow-hidden border-2 rounded-xl h-[500px] product-card cursor-pointer">
                    <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                        @if ($product->productImage->isNotEmpty())
                            <img src="{{ asset('/storage/' . $product->productImage->first()->file_path) }}"
                                alt="{{ $product->productImage->first()->alt_text ?? $product->name }}"
                                loading="lazy" style="transition-duration:8000ms">
                        @endif
                        <div class="p-4">
                            <div class="text-base font-semibold mb-2">{{ $product->name }}</div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($product->categories as $category)
                                    <span class="text-xs text-white px-2 py-1 rounded"
                                        style="background-color: {{ $primaryColor }}">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 text-center p-4 bg-white">
                            <div class="text-base font-semibold">{{ $product->name }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function productDetail() {
        return {
            isModalOpen: false,
            isImageModalOpen: false,
            productName: '',
            selectedImage: '',
            openModal(productName) {
                this.productName = productName;
                this.isModalOpen = true;
                Livewire.dispatch('setNameForm', {
                    name: productName
                });
            },
            closeModal() {
                this.isModalOpen = false;
                Livewire.dispatch('resetForm');
            },
            openImageModal(imageSrc) {
                this.selectedImage = imageSrc;
                this.isImageModalOpen = true;
            },
            closeImageModal() {
                this.isImageModalOpen = false;
            }
        }
    }
</script>
