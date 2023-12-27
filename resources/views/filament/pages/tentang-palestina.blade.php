<x-filament-panels::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        
        <div class="mt-3">
            <x-filament::button wire:click="save">
                Save Changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
