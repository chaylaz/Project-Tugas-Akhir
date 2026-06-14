<?php

namespace App\Filament\Resources\Tagihans\Tables;

use App\Models\Tagihan as TagihanModel;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class TagihansTable
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
                    ->color(function (string $state): string {
                        if ($state === 'belum') {
                            return 'warning';
                        } elseif ($state === 'lunas') {
                            return 'success';
                        } else {
                            return 'gray';
                        }
                    }),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y'),
            ])
            ->recordActions([
                Action::make('lunas')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('gray')
                    ->modalHeading('Konfirmasi Pelunasan')
                    ->modalDescription('Silakan pilih metode pembayaran untuk menandai tagihan ini sebagai lunas.')
                    ->modalSubmitActionLabel('Lanjutkan')
                    ->form([
                        Section::make('Detail Pembayaran')
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
                                            
                                        Placeholder::make('paket_layanan')
                                            ->label('Paket Layanan')
                                            ->content(fn ($record) => $record->paket->nama_paket ?? '-'),
                                            
                                        Placeholder::make('total')
                                            ->label('Total Pembayaran')
                                            ->content(fn ($record) => 'Rp ' . number_format($record->jumlah, 0, ',', '.'))
                                    ]),
                            ]),

                        Section::make('Pilih Metode Pembayaran')
                            ->schema([
                                Select::make('metode_pembayaran')
                                    ->label('Metode Pembayaran')
                                    ->options([
                                        'tunai' => 'Tunai',
                                        'non_tunai' => 'Non Tunai',
                                    ])
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, $set) {
                                        // Jika memilih 'non_tunai', set jenis_non_tunai ke null
                                        if ($state === 'non_tunai') {
                                            $set('jenis_non_tunai', null);
                                        }
                                    }),

                                // Jika memilih Tunai, tombol Selesai langsung aktif
                                Select::make('jenis_non_tunai')
                                    ->label('Pilih Pembayaran Non Tunai')
                                    ->options([
                                        'qris' => 'QRIS',
                                        'transfer' => 'Transfer',
                                    ])
                                    ->visible(function ($get) {
                                        return $get('metode_pembayaran') === 'non_tunai';
                                    })
                                    ->required(function ($get) {
                                        return $get('metode_pembayaran') === 'non_tunai';
                                    })
                                    ->disabled(function ($get) {
                                        // Menonaktifkan jenis_non_tunai jika metode_pembayaran bukan 'non_tunai'
                                        return $get('metode_pembayaran') !== 'non_tunai';
                                    })
                            ])
                    ])
                    ->visible(function ($record) {
                        return $record->status_pembayaran === 'belum';
                    }) // Menampilkan aksi ini hanya jika status_pembayaran adalah 'belum'
                    ->action(function ($record) {

                        // Ubah status tagihan menjadi lunas (ini memicu model event di Tagihan.php)
                        $record->update([
                            'status_pembayaran' => 'lunas',
                        ]);

                        // Kirim notifikasi bahwa tagihan telah dilunasi
                        Notification::make()
                            ->title('Tagihan berhasil dilunasi')
                            ->success()
                            ->send();
                    }),
             ])
            ->filters([
                SelectFilter::make('paket_layanan_id')
                    ->label('Paket Layanan')
                    ->relationship('paket', 'nama_paket'),
            ])
            ->toolbarActions([
                //
            ]);
    }
}