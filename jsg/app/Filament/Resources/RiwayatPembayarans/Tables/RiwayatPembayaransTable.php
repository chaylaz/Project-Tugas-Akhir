<?php

namespace App\Filament\Resources\RiwayatPembayarans\Tables;

use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
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
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->modalHeading('Detail Pembayaran')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->form([
                        Section::make('Informasi Pelanggan')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('nama')
                                            ->label('Nama Pelanggan')
                                            ->content(fn ($record) => $record->pelanggan->nama ?? '-'),

                                        Placeholder::make('nik')
                                            ->label('NIK')
                                            ->content(fn ($record) => $record->pelanggan?->user?->nik ?? '-'),

                                        Placeholder::make('alamat')
                                            ->label('Alamat')
                                            ->content(fn ($record) => $record->pelanggan->alamat ?? '-'),

                                        Placeholder::make('no_telepon')
                                            ->label('No. Telepon')
                                            ->content(fn ($record) => $record->pelanggan->no_telepon ?? '-'),
                                    ]),
                            ]),

                        Section::make('Informasi Tagihan')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('paket_layanan')
                                            ->label('Paket Layanan')
                                            ->content(fn ($record) => $record->paket->nama_paket ?? '-'),

                                        Placeholder::make('periode_info')
                                            ->label('Periode')
                                            ->content(fn ($record) => $record->periode ?? '-'),

                                        Placeholder::make('jumlah_info')
                                            ->label('Jumlah Pembayaran')
                                            ->content(fn ($record) => 'Rp ' . number_format($record->jumlah, 0, ',', '.')),

                                        Placeholder::make('status')
                                            ->label('Status')
                                            ->content(fn ($record) => ucfirst($record->status_pembayaran)),

                                        Placeholder::make('jatuh_tempo')
                                            ->label('Jatuh Tempo')
                                            ->content(fn ($record) => $record->due_date?->format('d M Y') ?? '-'),

                                        Placeholder::make('dilunasi_pada')
                                            ->label('Dilunasi Pada')
                                            ->content(fn ($record) => $record->updated_at?->format('d M Y H:i') ?? '-'),
                                    ]),
                            ]),
                    ]),
            ])
            ->toolbarActions([]);
    }
}