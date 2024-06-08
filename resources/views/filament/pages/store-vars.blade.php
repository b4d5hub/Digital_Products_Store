<x-filament-panels::page>
        <form wire:submit.prevent="submit">
            {{ $this->form }}
            <x-filament::button type="submit" style="margin-top:10px;">
                Save Changes
            </x-filament::button>
        </form>
</x-filament-panels::page>
