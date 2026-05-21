<?php

namespace App\Filament\Resources\RiwayatPembayarans\Pages;

use App\Filament\Resources\RiwayatPembayarans\RiwayatPembayaranResource;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatPembayarans extends ListRecords
{
    protected static string $resource = RiwayatPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}