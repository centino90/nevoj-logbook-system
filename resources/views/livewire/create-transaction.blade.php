<form wire:submit.prevent="submit" method="POST">

    <div class="p-4">
        {{ $this->form }}

        <x-filament::button type="submit" class="w-full mt-4">
            Submit Transaction
        </x-filament::button>
    </div>

</form>
