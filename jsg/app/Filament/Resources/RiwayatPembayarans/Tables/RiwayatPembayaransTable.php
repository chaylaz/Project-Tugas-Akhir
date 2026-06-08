<?php

namespace App\Filament\Resources\RiwayatPembayarans\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RiwayatPembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable(),

                TextColumn::make('paket.nama_paket')
                    ->label('Paket Layanan')
                    ->searchable(),

                TextColumn::make('periode')
                    ->label('Periode')
                    ->searchable(),

                TextColumn::make('jumlah')
                    ->label('Jumlah')
                    ->money('IDR', true),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'lunas' ? 'success' : 'warning'),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),

                TextColumn::make('updated_at')
                    ->label('Dilunasi Pada')
                    ->dateTime('d M Y H:i'),
            ])
            ->defaultSort('updated_at', 'desc')
            ->recordActions([])
            ->toolbarActions([]);
    }
}