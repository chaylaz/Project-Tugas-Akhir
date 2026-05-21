<?php

namespace App\Filament\Resources\Tagihans;

use App\Filament\Resources\Tagihans\Pages\ListTagihans;
use App\Filament\Resources\Tagihans\Schemas\TagihanForm;
use App\Filament\Resources\Tagihans\Tables\TagihansTable;
use App\Models\Tagihan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TagihanResource extends Resource
{
    protected static ?string $model = Tagihan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Tagihan';
    protected static ?string $modelLabel = 'Tagihan';
    protected static ?string $pluralModelLabel = 'Tagihan';
    protected static ?string $slug = 'tagihan';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return TagihanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagihansTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('status_pembayaran', 'belum');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTagihans::route('/'),
        ];
    }
}