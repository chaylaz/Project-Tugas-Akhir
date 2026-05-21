<?php

namespace App\Filament\Resources\RiwayatPembayarans\Pages;

use App\Filament\Resources\RiwayatPembayarans\RiwayatPembayaranResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatPembayaran extends EditRecord
{
    protected static string $resource = RiwayatPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
