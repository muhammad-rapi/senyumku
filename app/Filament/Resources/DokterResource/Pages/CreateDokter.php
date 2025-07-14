<?php

namespace App\Filament\Resources\DokterResource\Pages;

use App\Filament\Resources\DokterResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateDokter extends CreateRecord
{
    protected static string $resource = DokterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Create the User record first
        $user = User::create([
            'name' => $data['nama'], // Use the Pasien's name for the user's name
            'email' => strtolower(trim(str_replace(' ', '', $data['nama']))) . '@gmail.com', // Get email from the form
            'password' => Hash::make('password'), // Hash the password from the form
        ]);

        // 2. Assign the 'pasien' role to the newly created user
        $user->assignRole('pasien');

        // 3. Attach the user_id to the data that will be used to create the Pasien record
        $data['user_id'] = $user->id;

        return $data;
    }
}
