<div class="rounded-xl" style="border: 1px solid {{ $primaryColor }}">
    <div
        class="relative top-5 font-semibold text-center p-5 mx-12 rounded-xl">
        <h2 class="md:text-xl text-md">{{ $form->name }} <span
                style="color: {{ $primaryColor }}">{{ $this->title }}</span></h2>
    </div>
    <form wire:submit="submitForm"
        class="p-6 !pt-10 rounded-lg">
        @csrf

        <div class="grid grid-cols-1 gap-5">
            @foreach ($formFields as $field)
                <div class="col-span-2 sm:col-span-1 text-left">
                    <label for="{{ $field->name }}" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ $field->label }}
                        @if ($field->is_required)
                            <span class="text-red-500 ml-1">*</span>
                        @endif
                    </label>

                    @switch($field->type)
                        @case('textarea')
                            <textarea id="{{ $field->name }}" wire:model="formData.{{ $field->name }}"
                                class="w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md focus:outline-none transition duration-150 ease-in-out"
                                style="border-color: #ccc;"
                                x-on:focus="$el.style.boxShadow = '0 0 0 3px {{ $primaryColor }}40'; $el.style.borderColor = '{{ $primaryColor }}';"
                                x-on:blur="$el.style.boxShadow = 'none'; $el.style.borderColor = '#ccc';" rows="3"
                                @if ($field->is_required) required @endif></textarea>
                        @break

                        @case('select')
                            <select id="{{ $field->name }}" wire:model="formData.{{ $field->name }}"
                                class="w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md focus:outline-none transition duration-150 ease-in-out"
                                style="border-color: #ccc;"
                                x-on:focus="$el.style.boxShadow = '0 0 0 3px {{ $primaryColor }}40'; $el.style.borderColor = '{{ $primaryColor }}';"
                                x-on:blur="$el.style.boxShadow = 'none'; $el.style.borderColor = '#ccc';"
                                @if ($field->is_required) required @endif>
                                <option value="">Chọn {{ strtolower($field->label) }}</option>
                                @foreach (explode('|', $field->options) as $option)
                                    @php
                                        $optionParts = explode(',', $option);
                                        $optionValue = trim($optionParts[0]);
                                        $optionLabel = isset($optionParts[1]) ? trim($optionParts[1]) : $optionValue;
                                    @endphp
                                    <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                @endforeach
                            </select>
                        @break

                        @case('radio')
                            <div class="mt-2">
                                @foreach (explode('|', $field->options) as $option)
                                    @php
                                        $optionParts = explode(',', $option);
                                        $optionValue = trim($optionParts[0]);
                                        $optionLabel = isset($optionParts[1]) ? trim($optionParts[1]) : $optionValue;
                                    @endphp
                                    <div class="flex items-center mb-2">
                                        <input type="radio" id="{{ $field->name }}_{{ $optionValue }}"
                                            name="{{ $field->name }}" value="{{ $optionValue }}"
                                            wire:model="formData.{{ $field->name }}" class="mr-2"
                                            @if ($field->is_required) required @endif>
                                        <label for="{{ $field->name }}_{{ $optionValue }}" class="text-sm text-gray-700">
                                            {{ $optionLabel }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @break

                        @case('file')
                            <input type="file" id="{{ $field->name }}" wire:model="formData.{{ $field->name }}"
                                class="w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md focus:outline-none transition duration-150 ease-in-out"
                                style="border-color: #ccc;"
                                x-on:focus="$el.style.boxShadow = '0 0 0 3px {{ $primaryColor }}40'; $el.style.borderColor = '{{ $primaryColor }}';"
                                x-on:blur="$el.style.boxShadow = 'none'; $el.style.borderColor = '#ccc';"
                                @if ($field->is_required) required @endif>
                        @break

                        @default
                            <input type="{{ $field->type }}" id="{{ $field->name }}"
                                wire:model="formData.{{ $field->name }}"
                                class="w-full px-3 py-2 text-base text-gray-700 border border-gray-300 rounded-md focus:outline-none transition duration-150 ease-in-out"
                                style="border-color: #ccc;"
                                x-on:focus="$el.style.boxShadow = '0 0 0 3px {{ $primaryColor }}40'; $el.style.borderColor = '{{ $primaryColor }}';"
                                x-on:blur="$el.style.boxShadow = 'none'; $el.style.borderColor = '#ccc';"
                                @if ($field->is_required) required @endif>
                    @endswitch

                    @error("formData.$field->name")
                        <p class="mt-1 text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="button-main flex justify-end mt-6">
            <button type="submit" class="cssbuttons-io-button" wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed">
                <span wire:loading.remove>{{ $form->submit_button_text ?? 'Gửi' }}</span>
                <span wire:loading>Đang xử lý...</span>
                <div class="icon">
                    <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                        wire:loading.remove>
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path
                            d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"
                            fill="currentColor"></path>
                    </svg>
                    <svg class="animate-spin" wire:loading height="24" width="24" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </button>
        </div>
    </form>

    <div x-data="{ showMessage: false, message: '', type: '' }" x-init="window.addEventListener('show-message', (event) => {
        showMessage = true;
        message = event.detail[0].message;
        type = event.detail[0].type;
        setTimeout(() => { showMessage = false }, 5000);
    })" x-show="showMessage"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-90"
        class="fixed bottom-5 right-0 left-0 px-4 w-2/3 mx-auto text-center py-2 rounded-md shadow-lg text-white font-semibold"
        :class="{ 'bg-green-500 bg-opacity-70': type === 'success', 'bg-red-500': type === 'error' }"
        style="display: none;">
        <p x-text="message"></p>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('show-message', (data) => {
            window.dispatchEvent(new CustomEvent('show-message', {
                detail: data
            }));
        });
    });
</script>
