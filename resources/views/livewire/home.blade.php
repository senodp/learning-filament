<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    @if(session()->has('message'))
        <div class="text-2xl">
            {{ session('message') }}
        </div>
    @endif
    <form method="post" wire:submit="save">
        {{ $this->form }}
        <button type="submit" class="mt-4 bg-green-500 w-40 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Save
        </button>
    </form>
    
</div>
