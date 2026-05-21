<?php

namespace App\Filament\Resources\Tagihans\Pages;

use App\Filament\Resources\Tagihans\TagihanResource;
use Filament\Resources\Pages\ListRecords;

class ListTagihans extends ListRecords
{
    protected static string $resource = TagihanResource::class;

    // Hapus tombol "New Tagihan"
    protected function getHeaderActions(): array
    {
        return [];
    }
}