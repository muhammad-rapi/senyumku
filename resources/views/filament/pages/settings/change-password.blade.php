<x-filament-panels::page>
    <x-filament-panels::form wire:submit="changePassword">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="[
                \Filament\Forms\Components\Actions\Action::make('changePassword')
                    ->label('Ganti Password')
                    ->submit('form'), // <-- Tambahkan nama form di sini
            ]"
        />
    </x-filament-panels::form>
</x-filament-panels::page>