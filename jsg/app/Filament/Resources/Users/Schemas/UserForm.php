<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('pelanggan_id')
                    ->label('Pelanggan')
                    ->relationship('pelanggan', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('nik')
                    ->label('NIK')
                    ->unique(ignoreRecord: true)
                    ->required(),
            ]);
    }
}
