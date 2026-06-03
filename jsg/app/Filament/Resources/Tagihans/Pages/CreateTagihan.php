<?php

namespace App\Filament\Resources\Tagihans\Pages;

use App\Filament\Resources\Tagihans\TagihanResource;
use App\Models\Pelanggan;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTagihan extends CreateRecord
{
    protected static string $resource = TagihanResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id() ?? 1;

        return $data;
    }

    protected function afterCreate(): void
    {
        $pelanggan = Pelanggan::find($this->record->pelanggan_id);

        if ($pelanggan && $this->record->status_pembayaran === 'belum') {
            $pelanggan->update([
                'status_layanan' => 'non-aktif',
            ]);
        }
    }
}