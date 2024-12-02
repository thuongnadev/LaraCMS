<div class="max-w-7xl mx-auto" x-data="pricingDesign()">
    <div x-data="{
        activeTab: @entangle('activeTab'),
        init() {
            this.updateUI();
            Livewire.on('tab-changed', (event) => {
                this.activeTab = event.detail.tab;
                this.updateUI();
            });
        },
        updateUI() {
            this.$nextTick(() => {
                this.updateSlider();
                this.updateTextColor();
            });
        },
        updateSlider() {
            const activeTabElement = this.$refs[`tab${this.activeTab}`];
            if (activeTabElement) {
                this.$refs.slider.style.left = `${activeTabElement.offsetLeft}px`;
                this.$refs.slider.style.width = `${activeTabElement.offsetWidth}px`;
            }
        },
        updateTextColor() {
            Object.keys(this.$refs).forEach(key => {
                if (key.startsWith('tab')) {
                    const tabElement = this.$refs[key];
                    if (key === `tab${this.activeTab}`) {
                        tabElement.classList.add('text-white');
                        tabElement.classList.remove('text-gray-700');
                    } else {
                        tabElement.classList.remove('text-white');
                        tabElement.classList.add('text-gray-700');
                    }
                }
            });
        },
        setActiveTab(tab) {
            if (this.activeTab !== tab) {
                $wire.setActiveTab(tab);
            }
            this.activeTab = tab;
            this.updateUI();
        }
    }" class="relative mb-12 overflow-hidden" wire:ignore>
        <div class="flex justify-center relative z-10">
            @foreach ($pricingPackages as $key => $package)
                <button x-ref="tab{{ $key }}" @click="setActiveTab('{{ $key }}')"
                    :class="{ 'text-white': activeTab === '{{ $key }}', 'text-gray-700': activeTab !== '{{ $key }}' }"
                    class="px-6 py-3 font-semibold transition-all duration-300 rounded-full z-10 relative mx-0 lg:mx-2 text-nowrap text-xs lg:text-sm">
                    {{ $package['name'] }}
                </button>
            @endforeach
        </div>
        <div x-ref="slider" class="absolute top-0 h-full transition-all duration-300 ease-in-out rounded-full"
            style="left: 0; width: 0; background: {{ $primaryColor }}">
        </div>
    </div>
    <div
        class="grid gap-4 grid-cols-1 
        @if ($smColumns == 1) sm:grid-cols-1
        @elseif($smColumns == 2) sm:grid-cols-2
        @elseif($smColumns == 3) sm:grid-cols-3
        @elseif($smColumns == 4) sm:grid-cols-4 @endif
        @if ($mdColumns == 1) md:grid-cols-1
        @elseif($mdColumns == 2) md:grid-cols-2
        @elseif($mdColumns == 3) md:grid-cols-3
        @elseif($mdColumns == 4) md:grid-cols-4 @endif
        @if ($lgColumns == 1) lg:grid-cols-1
        @elseif($lgColumns == 2) lg:grid-cols-2
        @elseif($lgColumns == 3) lg:grid-cols-3
        @elseif($lgColumns == 4) lg:grid-cols-4 @endif">
        @isset($pricingPackages[$activeTab]['plans'])
            @foreach ($pricingPackages[$activeTab]['plans'] as $index => $plan)
                <div
                    class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-gray-200 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                    <div class="px-8 py-6 border-b-2 border-gray-100">
                        <div class="text-base font-bold" style="color: {{ $primaryColor }}">{{ $plan['name'] }}</div>
                    </div>
                    <div class="px-8 py-6">
                        <ul class="space-y-4">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex items-start">
                                    <div class="flex-shrink-0">
                                        @if ($feature['included'])
                                            <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="ml-4 text-sm text-gray-700">
                                        {{ $feature['name'] }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="button-main mt-8 flex justify-center" x-on:click="openModal('{{ $plan['name'] }}')">
                            <button class="cssbuttons-io-button">
                                Báo giá
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
            @endforeach
        @endisset
    </div>

    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="closeModal()" x-cloak
        class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center h-full pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-white bg-opacity-30 transition-opacity"
                aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            @livewire('theme::form', ['form' => $form, 'name' => $selectedPlanName])
        </div>
        <div class="button-close fixed z-50 top-10 right-10">
            <button type="button" x-on:click="closeModal()">
                <span class="text">Đóng</span>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                            d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z">
                        </path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</div>


<script>
    function pricingDesign() {
        return {
            isModalOpen: false,
            planName: '',
            openModal(planName) {
                this.planName = planName;
                this.isModalOpen = true;
                Livewire.dispatch('setNameForm', {
                    name: planName
                });
            },
            closeModal() {
                this.isModalOpen = false;
                Livewire.dispatch('resetForm');
            },
        }
    }
</script>
