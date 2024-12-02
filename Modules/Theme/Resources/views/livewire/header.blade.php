<nav style="background-color: {{ $headerConfig['background_color'] ?? '#fff' }};" class="border-gray-200">
    @if (!empty($headerConfig))
        <div class="max-w-7xl mx-auto px-2 py-3">
            <div class="md:hidden">
                @if (!empty($headerConfig['contacts']))
                    <div class="grid grid-cols-2 gap-2 mb-4 text-xs">
                        @foreach ($headerConfig['contacts'] as $contact)
                            <div class="flex items-center justify-start">
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
                                    <i class="{{ $icon }} mr-1" style="color: {{ $primaryColor }};"></i>
                                @endif
                                <span class="font-medium truncate">{{ $contact['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center justify-between w-full md:w-auto">
                    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                        <img style="height: {{ $headerConfig['logo_size'] . 'px' ?? '30px' }}"
                            src="{{ asset('storage/logos/logo.png') }}" alt="Logo" loading="lazy" class="h-8 md:h-12" />
                    </a>
                    <button data-collapse-toggle="navbar-multi-level" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        aria-controls="navbar-multi-level" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>

                <div class="hidden md:flex flex-col items-end">
                    @if (!empty($headerConfig['contacts']))
                        <div class="grid grid-cols-2 gap-2">
                            @foreach ($headerConfig['contacts'] as $contact)
                                <div class="flex items-center text-sm lg:text-base text-gray-600">
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
                                        <i class="{{ $icon }} mr-2" style="color: {{ $primaryColor }};"></i>
                                    @endif
                                    <span class="font-medium mr-1">{{ $contact['name'] }}:</span>
                                    <span class="font-semibold"
                                        style="color: {{ $primaryColor }};">{{ $contact['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</nav>
