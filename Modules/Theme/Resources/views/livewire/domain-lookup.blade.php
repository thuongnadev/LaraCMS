<div class="flex flex-col items-center">
    <form wire:submit.prevent="redirectToDomainLookupDetail" class="w-full max-w-2xl">
        <div class="form-group w-full">
            <div class="relative flex overflow-hidden w-full rounded-lg shadow-sm">
                <input type="text" wire:model="domain" placeholder="Nhập tên miền của bạn"
                    class="w-7 flex-grow p-2 md:p-4 !pl-12 text-lg text-gray-800 md:placeholder:text-xl placeholder:text-sm placeholder-gray-400 bg-white border-2 focus:outline-none transition duration-300 ease-in-out rounded-l-lg"
                    style="border: 1px solid {{ $this->primaryColor ?? '#10b981' }}" />
                <span class="absolute left-4 top-1/2 transform -translate-y-1/2"
                    style="color: {{ $this->primaryColor }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </span>
                <button id="submit-button" style="background: {{ $this->primaryColor ?? '#10b981'}}" type="submit"
                    class="text-white px-4 py-2 md:px-8 md:py-4 focus:outline-none transition duration-300 ease-in-out rounded-r-lg font-semibold transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove class="text-xs md:text-base">Kiểm tra ngay</span>
                    <span wire:loading class="text-xs md:text-base">
                        <svg class="animate-spin -ml-1 mr-2 md:mr-3 h-5 w-5 text-white inline-block"
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
        </div>
    </form>
</div>
