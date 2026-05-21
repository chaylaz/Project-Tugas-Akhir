<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tagihan extends Model
{
    protected $table = 'tagihan';

    protected $fillable = [
        'pelanggan_id',
        'paket_layanan_id',
        'created_by',
        'periode',
        'jumlah',
        'due_date',
        'status_pembayaran',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_layanan_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::updated(function (Tagihan $tagihan): void {
            if (
                $tagihan->wasChanged('status_pembayaran') &&
                $tagihan->status_pembayaran === 'lunas'
            ) {
                $pelanggan = $tagihan->pelanggan;

                if ($pelanggan) {
                    $pelanggan->update([
                        'status_layanan' => 'aktif',
                        'masa_aktif' => now()->addMonth()->toDateString(),
                    ]);
                }
            }
        });
    }
}