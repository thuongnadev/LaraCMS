<x-filament::page>
    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}
        <x-filament::button type="submit" form="submit" class="btn-primary">
            Lưu
        </x-filament::button>
    </form>


    <style>
        .palette-color-picker-item-active {
            width: 40px !important;
            transition: 0.3s ease-in-out;
        }
    </style>
</x-filament::page>