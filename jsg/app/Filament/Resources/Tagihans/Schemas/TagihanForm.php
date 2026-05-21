<?php

namespace App\Filament\Resources\Tagihans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class TagihanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Pilih Pelanggan
                Forms\Components\Select::make('pelanggan_id')
                    ->label('Pelanggan')
                    ->relationship('pelanggan', 'nama')
                    ->searchable()
                    ->required(),

                // 🔥 Pilih Paket Layanan
                Forms\Components\Select::make('paket_layanan_id')
                    ->label('Paket Layanan')
                    ->relationship('paket', 'nama_paket')
                    ->required(),

                // Periode Tagihan
                Forms\Components\TextInput::make('periode')
                    ->label('Periode (contoh: Mei 2026)')
                    ->required(),

                // Jumlah Tagihan
                Forms\Components\TextInput::make('jumlah')
                    ->label('Jumlah')
                    ->prefix('Rp')
                    ->required(),

                // Jatuh Tempo
                Forms\Components\DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->required(),

                // Status Pembayaran
                Forms\Components\Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'belum' => 'Belum',
                        'lunas' => 'Lunas',
                    ])
                    ->default('belum')
                    ->required(),
            ]);
    }
}