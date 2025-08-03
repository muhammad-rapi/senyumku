<?php

namespace App\Filament\Pages\Settings;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Ganti Password';
    protected static string $view = 'filament.pages.settings.change-password';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('admin');
    }
    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Password Saat Ini')
                    ->password()
                    ->required()
                    ->currentPassword()
                    ->revealable(),
                TextInput::make('new_password')
                    ->label('Password Baru')
                    ->password()
                    ->required()
                    ->revealable()
                    ->rule(Password::default()),
                TextInput::make('new_password_confirmation')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->required()
                    ->revealable()
                    ->same('new_password'),
            ])
            ->statePath('data');
    }

    public function changePassword(): void
    {
        $data = $this->form->getState();

        auth()->user()->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Password berhasil diperbarui.')
            ->success()
            ->send();
    }
}
