<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pelanggan;
use App\Models\Tagihan;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelanggan', Pelanggan::count())
            ->description('Semua pelanggan terdaftar')
            ->color('primary'),

            // Stat::make('Pelanggan Aktif', Pelanggan::where('status', 'aktif')->count())
            //     ->description('Status aktif')
            //     ->color('success'), 

            Stat::make('Belum Bayar', Tagihan::where('status_pembayaran', 'belum')->count())
                ->description('Tagihan pending')
                ->color('danger'),

            Stat::make('Uang Masuk', 'Rp ' . number_format(Tagihan::where('status_pembayaran', 'lunas')->whereMonth('updated_at', now()->month)->whereYear('updated_at', now()->year)->sum('jumlah'), 0, ',', '.'))
                ->description('Total pendapatan bulan ini')
                ->color('success'),
        ];
    }
}
