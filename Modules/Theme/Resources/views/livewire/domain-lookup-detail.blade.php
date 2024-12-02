<div x-data="domainLookupDesign()">
    <div class="flex flex-col items-center py-8 mb-5" style="background-color: {{ $this->primaryColor }}">
        <form wire:submit.prevent="fetchDomainData" class="w-full max-w-2xl">
            <div class="form-group w-full">
                <div class="relative flex overflow-hidden w-full border-2 border-white rounded-lg shadow-sm">
                    <input type="text" wire:model.defer="domain" placeholder="Nhập tên miền của bạn"
                        class="flex-grow p-4 pl-12 text-lg text-gray-800 placeholder-gray-400 bg-white focus:outline-none transition duration-300 ease-in-out rounded-l-lg"
                        style="border: 1px solid {{ $this->primaryColor }}" />
                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2"
                        style="color: {{ $this->primaryColor }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </span>
                    <button id="submit-button" style="background: {{ $this->primaryColor }}" type="submit"
                        class="text-white px-8 py-4 focus:outline-none transition duration-300 ease-in-out rounded-r-lg text-lg font-semibold transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled" wire:target="fetchDomainData">
                        <span wire:loading.remove wire:target="fetchDomainData" class="text-xs lg:text-base">Kiểm tra
                            ngay</span>
                        <span wire:loading wire:target="fetchDomainData">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Đang kiểm tra...
                        </span>
                    </button>
                </div>
                @error('domain')
                    <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                @enderror
                @error('domainCheck')
                    <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>

    <div class="max-w-7xl mx-auto px-2 grid grid-cols-1 md:grid-cols-3 gap-5">
        <div x-data="{
            isOpen: false,
            groupNames: {
                'popular': 'Phổ biến',
                'vietnam': 'Việt Nam',
                'international': 'Quốc tế'
            }
        }" class="md:col-span-1 bg-gray-50 shadow-xl p-4 rounded-xl">
            <div>
                <h2 class="text-2xl font-bold mb-4 flex justify-between items-center">
                    Chọn TLD
                    <button @click="isOpen = !isOpen" class="md:hidden focus:outline-none">
                        <svg class="w-6 h-6 transition-transform duration-300 ease-in-out"
                            :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                </h2>
                <div x-show="isOpen || window.innerWidth >= 768" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @if($tldGroups)
                        @foreach ($tldGroups as $groupName => $tlds)
                            <div class="rounded-md p-2 bg-white" style="border:1px solid {{ $this->primaryColor }}">
                                <h3 class="text-lg font-medium mb-2"
                                    x-text="groupNames['{{ $groupName ?? '' }}'] || '{{ ucfirst($groupName) }}'"></h3>
                                <div class="space-y-2">
                                    @if($tlds)
                                        @foreach ($tlds as $tld => $description)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="{{ $tld }}"
                                                       class="form-checkbox h-5 w-5 rounded-md outline-none"
                                                       style="color: {{ $this->primaryColor }}"
                                                    {{ in_array($tld, $selectedTlds) ? 'checked' : '' }}>
                                                <label for="{{ $tld }}"
                                                       class="ml-2 text-sm text-gray-700 flex items-center">
                                                    <span>{{ $description }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="md:col-span-2 bg-gray-50 shadow-xl p-4 rounded-xl" wire:poll.5000>
            <h2 class="text-2xl font-bold mb-4">Kết quả tìm kiếm</h2>
            @if ($isLoading)
                <div class="space-y-4">
                    @for ($i = 0; $i < 3; $i++)
                        <div
                            class="flex items-center justify-between p-4 sm:p-6 mb-4 bg-white rounded-xl animate-pulse">
                            <div class="flex-1">
                                <div class="h-6 bg-gray-200 rounded w-3/4 mb-2"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            </div>
                            <div class="w-24 h-10 bg-gray-200 rounded"></div>
                        </div>
                    @endfor
                </div>
            @elseif(!$isLoading && empty($domainData))
                <p class="text-gray-500 text-center py-4">Không có kết quả nào được tìm thấy.</p>
            @else
                @foreach ($domainData as $domain)
                    <div class="flex items-center justify-between p-4 sm:p-6 mb-4 transition duration-300 ease-in-out rounded-xl bg-white"
                        style="border:1px solid {{ $this->primaryColor }}">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold">
                                <span class="text-gray-800">{{ explode('.', $domain['domain'])[0] }}</span>
                                <span
                                    style="color: {{ $primaryColor }}">{{ substr($domain['domain'], strpos($domain['domain'], '.')) }}</span>
                            </h3>
                            @if ($domain['data']['status'] !== 'Không')
                                <p class="text-red-500 text-sm sm:text-base mt-1">Đã được đăng ký</p>
                            @else
                                <p class="text-green-500 text-sm sm:text-base mt-1">Có thể đăng ký</p>
                            @endif
                        </div>
                        @if ($domain['data']['status'] !== 'Không')
                            <div class="button-main mt-8 flex justify-center"
                                @click="openModal('{{ $domain['domain'] }}', {{ json_encode($domain['data']) }})">
                                <button class="cssbuttons-io-button">
                                    Xem thông tin
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
                        @else
                            <div class="button-main mt-8 flex justify-center"
                                @click="openConsultModal('{{ $domain['domain'] }}')">
                                <button class="cssbuttons-io-button">
                                    Tư vấn
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
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Modal chi tiết domain --}}
    <div x-show="isModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="closeModal()" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-white bg-opacity-80 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Thông tin chi tiết domain: <span x-text="selectedDomain"></span>
                    </h3>
                    <div class="mt-2" x-show="selectedDomainData">
                        <p><strong>Ngày đăng ký:</strong> <span
                                x-text="selectedDomainData?.registration_date || 'N/A'"></span></p>
                        <p><strong>Ngày hết hạn:</strong> <span
                                x-text="selectedDomainData?.expiration_date || 'N/A'"></span></p>
                        <p><strong>Nhà đăng ký:</strong> <span x-text="selectedDomainData?.registrar || 'N/A'"></span>
                        </p>
                        <p><strong>Trạng thái:</strong> <span x-text="selectedDomainData?.status || 'N/A'"></span></p>
                        <p><strong>DNS:</strong> <span
                                x-text="selectedDomainData?.name_servers?.join(', ') || 'N/A'"></span></p>
                    </div>
                    <div class="mt-2" x-show="!selectedDomainData">
                        <p>Không có thông tin chi tiết cho domain này.</p>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <div class="button-close">
                        <button type="button" @click="closeModal()">
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
            </div>
        </div>
    </div>

    {{-- Modal tư vấn --}}
    <div x-show="isConsultModalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-90" x-on:click.away="closeConsultModal()" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-white bg-opacity-30 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            @livewire('theme::form', ['form' => $form, 'name' => $selectedDomain])
        </div>
        <div class="button-close fixed z-50 top-10 right-10">
            <button type="button" x-on:click="closeConsultModal()">
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
    document.addEventListener('livewire:initialized', () => {
        let apiEndpoint, selectedTlds, domain, tldGroups;

        Livewire.on('updateUrl', (data) => {
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('domain', data[0].domain);
            history.pushState({}, '', newUrl);
        });

        const checkDomain = async (domain, tld) => {
            try {
                const fullDomain = `${domain}${tld}`;
                console.log(`Checking domain: ${fullDomain}`);

                const response = await fetch(`/api/check-domain?domain=${fullDomain}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log(`Response for ${fullDomain}:`, data);
                return {
                    domain: fullDomain,
                    data
                };
            } catch (error) {
                console.error(`Error checking domain ${domain}${tld}:`, error);
                return {
                    domain: `${domain}${tld}`,
                    error: `Failed to check domain: ${error.message}`
                };
            }
        };

        const updateDomainData = async () => {
            let domainParts = domain.split('.');
            let domainName = domainParts[0];
            let searchTld = domainParts.length > 1 ? '.' + domainParts.slice(1).join('.') : null;

            let tldsToCheck = [...selectedTlds];
            if (searchTld) {
                const allTlds = Object.values(tldGroups).flatMap(group => Object.keys(group));
                if (allTlds.includes(searchTld)) {
                    if (!selectedTlds.includes(searchTld)) {
                        tldsToCheck.push(searchTld);
                        await Livewire.dispatch('addTld', {
                            tld: searchTld
                        });
                        updateCheckboxes();
                    }
                    tldsToCheck = [searchTld, ...tldsToCheck.filter(tld => tld !== searchTld)];
                } else {
                    Livewire.dispatch('showError', {
                        message: `Đuôi tên miền ${searchTld} không được hỗ trợ.`
                    });
                    searchTld = null;
                }
            }

            try {
                const results = await Promise.all(tldsToCheck.map(tld => checkDomain(domainName, tld)));

                results.sort((a, b) => {
                    if (a.domain === domain) return -1;
                    if (b.domain === domain) return 1;
                    if (selectedTlds.includes(a.domain.split('.').pop()) && !selectedTlds
                        .includes(b.domain.split('.').pop())) return -1;
                    if (!selectedTlds.includes(a.domain.split('.').pop()) && selectedTlds
                        .includes(b.domain.split('.').pop())) return 1;
                    return 0;
                });

                Livewire.dispatch('updateDomainData', {
                    domainData: results.map(result => ({
                        domain: result.domain,
                        data: result.data && result.data.data ? result.data.data :
                            null,
                        error: result.error
                    })),
                    searchTld: searchTld
                });
            } catch (error) {
                console.error('Error in updateDomainData:', error);
                Livewire.dispatch('showError', {
                    message: 'Có lỗi xảy ra khi kiểm tra tên miền. Vui lòng thử lại sau.'
                });
            }
        };

        const updateCheckboxes = () => {
            selectedTlds.forEach(tld => {
                const checkbox = document.getElementById(tld);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        };

        const setupCheckboxListeners = () => {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', (event) => {
                    const tld = event.target.id;
                    if (event.target.checked) {
                        if (!selectedTlds.includes(tld)) {
                            selectedTlds.push(tld);
                        }
                    } else {
                        const index = selectedTlds.indexOf(tld);
                        if (index > -1) {
                            selectedTlds.splice(index, 1);
                        }
                    }
                    Livewire.dispatch('updateSelectedTlds', {
                        selectedTlds
                    });
                });
            });
        };

        const setupDomainInputListener = () => {
            const domainInput = document.querySelector('input[wire\\:model\\.defer="domain"]');
            if (domainInput) {
                domainInput.addEventListener('input', (event) => {
                    domain = event.target.value;
                });
            }
        };

        const setupFormSubmitListener = () => {
            const form = document.querySelector('form[wire\\:submit\\.prevent="fetchDomainData"]');
            if (form) {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    updateDomainData();
                });
            }
        };

        setupCheckboxListeners();
        setupDomainInputListener();
        setupFormSubmitListener();

        Livewire.on('tldListUpdated', () => {
            initializeVariables();
            updateCheckboxes();
            Livewire.dispatch('refreshDomainData');
        });

        Livewire.on('showError', (data) => {
            const errorElement = document.createElement('p');
            errorElement.classList.add('text-red-500', 'text-sm', 'mt-2', 'text-center');
            errorElement.textContent = data.message;

            const formGroup = document.querySelector('.form-group');
            formGroup.appendChild(errorElement);

            setTimeout(() => {
                errorElement.remove();
            }, 5000);
        });

        function initializeVariables() {
            apiEndpoint = @this.apiEndpoint;
            selectedTlds = @this.selectedTlds;
            domain = @this.domain;
            tldGroups = @this.tldGroups;
            updateDomainData();
        }

        initializeVariables();
    });

    function domainLookupDesign() {
        return {
            isModalOpen: false,
            isConsultModalOpen: false,
            selectedDomain: '',
            selectedDomainData: null,

            openModal(domain, data) {
                this.selectedDomain = domain;
                this.selectedDomainData = data && data.domain_name ? data : null;
                this.isModalOpen = true;
            },
            closeModal() {
                this.isModalOpen = false;
                this.selectedDomain = '';
                this.selectedDomainData = null;
            },
            openConsultModal(domain) {
                this.selectedDomain = domain;
                this.isConsultModalOpen = true;
                Livewire.dispatch('setNameForm', {
                    name: domain
                });
            },
            closeConsultModal() {
                this.isConsultModalOpen = false;
                Livewire.dispatch('resetForm');
            },
            changeCheckbox() {
                Livewire.dispatch('updateSelectTld');
            }
        }
    }
</script>
