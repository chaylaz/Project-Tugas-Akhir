<?php

namespace App\Filament\Resources\Pelanggans\Pages;

use App\Filament\Resources\Pelanggans\PelangganResource;
use App\Models\Tagihan;
use App\Models\User;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;

class CreatePelanggan extends CreateRecord
{
    protected static string $resource = PelangganResource::class;

    protected static bool $canCreateAnother = false;

    protected function afterCreate(): void
    {
        $pelanggan = $this->record;

        $today = Carbon::today();

        $indonesianMonths = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $englishMonth = $today->format('F');
        $year = $today->format('Y');
        $indonesianMonth = $indonesianMonths[$englishMonth] ?? $englishMonth;
        $periode = "{$indonesianMonth} {$year}";

        $adminId = User::orderBy('id')->first()?->id ?? 1;

        Tagihan::firstOrCreate(
            [
                'pelanggan_id' => $pelanggan->id,
                'periode' => $periode,
            ],
            [
                'paket_layanan_id' => $pelanggan->paket_layanan_id,
                'created_by' => $adminId,
                'jumlah' => $pelanggan->paket?->harga ?? 0,
                'due_date' => $today->toDateString(),
                'status_pembayaran' => 'belum',
            ]
        );

        $pelanggan->update([
            'status_layanan' => 'non-aktif',
            'masa_aktif' => $today->toDateString(),
        ]);
    }
}