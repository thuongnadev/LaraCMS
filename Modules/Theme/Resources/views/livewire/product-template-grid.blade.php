<div x-data="productGrid()">
    <div
        class="grid gap-4 grid-cols-1 
        {{ $smColumns ? 'sm:grid-cols-' . $smColumns : '' }}
        {{ $mdColumns ? 'md:grid-cols-' . $mdColumns : '' }}
        {{ $lgColumns ? 'lg:grid-cols-' . $lgColumns : '' }}">
        @forelse ($products ?? [] as $product)
            <div class="relative overflow-hidden border-2 rounded-xl h-[500px] product-card cursor-pointer">
                <div
                    x-on:click="openModal('{{ $product->productImage->isNotEmpty() ? asset('/storage/' . $product->productImage->first()->file_path) : '' }}', '{{ $product->name ?? 'Unnamed Product' }}')">
                    @if ($product->productImage && $product->productImage->isNotEmpty())
                        <img src="{{ asset('/storage/' . $product->productImage->first()->file_path) }}"
                            loading="lazy"
                            alt="{{ $product->productImage->first()->alt_text ?? $product->name ?? 'Product Image' }}"
                            style="transition-duration:8000ms"
                            class="w-full h-auto max-w-[400px]">
                    @else
                        <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">No image available</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <div class="text-base font-semibold mb-2">{{ $product->name ?? 'Unnamed Product' }}</div>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse ($product->categories ?? [] as $category)
                                <span class="text-xs text-white px-2 py-1 rounded"
                                    style="background-color: {{ $primaryColor ?? '#000000' }}">{{ $category->name ?? 'Uncategorized' }}</span>
                            @empty
                                <span class="text-xs text-gray-500">No categories</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 text-center p-4 bg-white">
                        <div class="text-sm md:text-base font-semibold">{{ $product->name ?? 'Unnamed Product' }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-gray-500">No products available</p>
            </div>
        @endforelse
    </div>

    <div x-show="isModalOpen" x-on:click.away="closeModal()" x-cloak class="fixed inset-0 z-50 overflow-hidden"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-white bg-opacity-80 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-7xl w-full relative">

                <div x-show="showConsultationForm" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="absolute top-0 bg-black bg-opacity-80 left-0 right-0 z-10 h-full">
                    <div class="p-4">
                        <div class="button-close absolute top-5 right-10 z-[100]">
                            <button type="button" x-on:click="closeConsultationForm()">
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
                        @livewire('theme::form', ['form' => $form, 'name' => $selectedProductName])
                    </div>
                </div>

                <div class="bg-white px-4 pt-5 pb-20 sm:p-6 sm:pb-16 max-h-[80vh] overflow-y-auto">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:text-left w-full">
                            <img :src="modalImage" alt="Product Image" class="w-full h-auto" loading="lazy">
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gray-50 px-4 py-3 sm:px-6 flex gap-3 justify-end sticky bottom-0 left-0 right-0">
                    <div class="button-close">
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
                    <div class="button-main">
                        <button type="submit" class="cssbuttons-io-button"
                            x-on:click="toggleConsultationForm('{{ $product->name ?? '' }}')">
                            <span>Tư vấn</span>
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

    @if (isset($linkMoreProduct) && $linkMoreProduct)
        <div class="button-main flex justify-center mt-10">
            <a href="{{ route($linkMoreProduct) }}">
                <button class="cssbuttons-io-button">
                    Xem thêm
                    <div class="icon">
                        <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                </button>
            </a>
        </div>
    @endif
</div>

<script>
    function productGrid() {
        return {
            isModalOpen: false,
            modalImage: '',
            showConsultationForm: false,
            selectedProductName: '',
            openModal(imageSrc, productName) {
                this.isModalOpen = true;
                this.modalImage = imageSrc || '';
                this.selectedProductName = productName || 'Unnamed Product';
            },
            closeModal() {
                this.isModalOpen = false;
                this.showConsultationForm = false;
            },
            toggleConsultationForm() {
                this.showConsultationForm = !this.showConsultationForm;
                if (typeof Livewire !== 'undefined' && Livewire.dispatch) {
                    Livewire.dispatch('setNameForm', {
                        name: this.selectedProductName
                    });
                }
            },
            closeConsultationForm() {
                this.showConsultationForm = false;
                if (typeof Livewire !== 'undefined' && Livewire.dispatch) {
                    Livewire.dispatch('resetForm');
                }
            },
        }
    }
</script>
