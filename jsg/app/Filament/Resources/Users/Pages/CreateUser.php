<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = bcrypt('123');

        // Set nama dari pelanggan yang dipilih
        if (!empty($data['pelanggan_id'])) {
            $pelanggan = \App\Models\Pelanggan::find($data['pelanggan_id']);
            if ($pelanggan) {
                $data['name'] = $pelanggan->nama;
            }
        }

        return $data;
    }
}
