<?php

namespace App\Filament\Resources\Pelanggans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;

class PelanggansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30),

                TextColumn::make('no_telepon')
                    ->label('No. Telepon'),

                TextColumn::make('paket.nama_paket')
                    ->label('Paket Layanan'),

                TextColumn::make('status_layanan')
                    ->label('Status Layanan')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'aktif' => 'success',
                        'non-aktif' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('masa_aktif')
                    ->label('Masa Aktif')
                    ->date('d M Y'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}