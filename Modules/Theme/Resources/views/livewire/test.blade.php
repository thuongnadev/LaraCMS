<div class="" x-data="productGridAndForm()">
    <div class="grid gap-4 grid-cols-4">
        @foreach ($products as $product)
            <div class="relative overflow-hidden border-2 rounded-xl h-[500px] product-card cursor-pointer">
                <div x-on:click="openModal('{{ asset($product->productImage->first()->file_path) }}')">
                    @if ($product->productImage->isNotEmpty())
                        <img src="{{ asset($product->productImage->first()->file_path) }}"
                            alt="{{ $product->productImage->first()->alt_text ?? $product->name }}"
                            style="transition-duration:8000ms">
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
                </div>
            </div>
        @endforeach
    </div>

    <div x-show="isModalOpen" x-on:click.away="closeModal()" x-cloak class="fixed inset-0 z-50 overflow-hidden"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="isModalOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle max-w-7xl w-full relative">

                <!-- Fixed Consultation Form at the top of modal -->
                <div x-show="showConsultationForm" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="absolute top-0 left-0 right-0 bg-white shadow-md z-10">
                    <div class="p-4">
                        @if ($form)
                            <div class="mt-8 bg-white shadow-md rounded-lg p-6">
                                <h2 class="text-2xl font-bold mb-4">{{ $form->name }}</h2>
                                <form @submit.prevent="submitForm">
                                    @foreach ($form->formFields as $field)
                                        <div class="mb-4">
                                            <label for="{{ $field->name }}"
                                                class="block text-sm font-medium text-gray-700">
                                                {{ $field->label }}
                                                @if ($field->is_required)
                                                    <span class="text-red-500">*</span>
                                                @endif
                                            </label>
                                            @switch($field->type)
                                                @case('text')
                                                @case('email')

                                                @case('tel')
                                                    <input type="{{ $field->type }}" id="{{ $field->name }}"
                                                        name="{{ $field->name }}" x-model="formData.{{ $field->name }}"
                                                        @if ($field->is_required) required @endif
                                                        @if ($field->min_length) minlength="{{ $field->min_length }}" @endif
                                                        @if ($field->max_length) maxlength="{{ $field->max_length }}" @endif
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        :class="{ 'border-red-500': errors.{{ $field->name }} }">
                                                    <p x-show="errors.{{ $field->name }}" x-text="errors.{{ $field->name }}"
                                                        class="mt-1 text-sm text-red-500"></p>
                                                @break

                                                @case('textarea')
                                                    <textarea id="{{ $field->name }}" name="{{ $field->name }}" rows="3" x-model="formData.{{ $field->name }}"
                                                        @if ($field->is_required) required @endif
                                                        @if ($field->min_length) minlength="{{ $field->min_length }}" @endif
                                                        @if ($field->max_length) maxlength="{{ $field->max_length }}" @endif
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                        :class="{ 'border-red-500': errors.{{ $field->name }} }"></textarea>
                                                    <p x-show="errors.{{ $field->name }}" x-text="errors.{{ $field->name }}"
                                                        class="mt-1 text-sm text-red-500"></p>
                                                @break
                                            @endswitch
                                        </div>
                                    @endforeach

                                    <div class="mt-6">
                                        <button type="submit"
                                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ $form->submit_button_text ?? 'Gửi' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <p class="mt-8 text-center text-gray-500">Không tìm thấy cấu hình form.</p>
                        @endif
                    </div>
                </div>

                <!-- Product Image and Content -->
                <div class="bg-white px-4 pt-5 pb-20 sm:p-6 sm:pb-16 max-h-[80vh] overflow-y-auto">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:text-left w-full">
                            <img :src="modalImage" alt="Product Image" class="w-full h-auto">
                        </div>
                    </div>
                </div>

                <!-- Modal Footer with Buttons -->
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse sticky bottom-0 left-0 right-0">
                    <button type="button" x-on:click="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Đóng
                    </button>
                    <button type="button" x-on:click="toggleConsultationForm()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-blue-300 shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tư vấn
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="button-main flex justify-center mt-10">
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
    </div>
</div>

<script>
    function productGridAndForm() {
        return {
            isModalOpen: false,
            modalImage: '',
            showConsultationForm: false,
            formData: {},
            errors: {},
            openModal(imageSrc) {
                this.isModalOpen = true;
                this.modalImage = imageSrc;
            },
            closeModal() {
                this.isModalOpen = false;
                this.showConsultationForm = false;
            },
            toggleConsultationForm() {
                this.showConsultationForm = !this.showConsultationForm;
            },
            closeConsultationForm() {
                this.showConsultationForm = false;
                this.formData = {};
                this.errors = {};
            },
            submitForm() {
                this.errors = {};
                let isValid = true;

                // Validate each field
                @foreach ($form->formFields as $field)
                    if (@json($field->is_required) && !this.formData.{{ $field->name }}) {
                        this.errors.{{ $field->name }} = '{{ $field->label }} là trường bắt buộc.';
                        isValid = false;
                    }
                    @if ($field->min_length)
                        if (this.formData.{{ $field->name }} && this.formData.{{ $field->name }}.length <
                            {{ $field->min_length }}) {
                            this.errors.{{ $field->name }} =
                                '{{ $field->label }} phải có ít nhất {{ $field->min_length }} ký tự.';
                            isValid = false;
                        }
                    @endif
                    @if ($field->max_length)
                        if (this.formData.{{ $field->name }} && this.formData.{{ $field->name }}.length >
                            {{ $field->max_length }}) {
                            this.errors.{{ $field->name }} =
                                '{{ $field->label }} không được vượt quá {{ $field->max_length }} ký tự.';
                            isValid = false;
                        }
                    @endif
                    @if ($field->type === 'email')
                        if (this.formData.{{ $field->name }} && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData
                                .{{ $field->name }})) {
                            this.errors.{{ $field->name }} = 'Vui lòng nhập một địa chỉ email hợp lệ.';
                            isValid = false;
                        }
                    @endif
                    @if ($field->type === 'tel')
                        if (this.formData.{{ $field->name }} && !/^[0-9]{10,11}$/.test(this.formData
                                .{{ $field->name }})) {
                            this.errors.{{ $field->name }} = 'Vui lòng nhập một số điện thoại hợp lệ (10-11 chữ số).';
                            isValid = false;
                        }
                    @endif
                @endforeach

                if (isValid) {
                    console.log('Form submitted:', this.formData);
                    alert('Cảm ơn bạn đã gửi yêu cầu tư vấn. Chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.');
                    this.closeConsultationForm();
                }
            }
        }
    }
</script>
