<?php

namespace App\Filament\Resources\Pelanggans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PelangganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->required(),
                Textarea::make('alamat')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('no_telepon')
                    ->tel()
                    ->required(),
                Select::make('paket_layanan_id')
                    ->label('Paket Layanan')
                    ->relationship('paket', 'nama_paket')
                    ->required(),
                Select::make('status_layanan')
                    ->options(['aktif' => 'Aktif', 'non-aktif' => 'Non aktif', 'cutoff' => 'Cutoff'])
                    ->default('aktif')
                    ->required(),
                DatePicker::make('masa_aktif')
                    ->required(),
            ]);
    }
}
