<?php

namespace App\Filament\Resources\RiwayatPembayarans;

use App\Filament\Resources\RiwayatPembayarans\Pages\ListRiwayatPembayarans;
use App\Filament\Resources\RiwayatPembayarans\Tables\RiwayatPembayaransTable;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RiwayatPembayaranResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'periode';

    protected static ?string $navigationLabel = 'Riwayat Pembayaran';
    protected static ?string $modelLabel = 'Riwayat Pembayaran';
    protected static ?string $pluralModelLabel = 'Riwayat Pembayaran';
    protected static ?string $slug = 'riwayat-pembayaran';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return RiwayatPembayaransTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status_pembayaran', 'lunas');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRiwayatPembayarans::route('/'),
        ];
    }
}