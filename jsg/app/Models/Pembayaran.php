<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'tagihan_id',
        'tanggal_pembayaran',
        'metode',
        'jumlah_bayar',
        'midtrans_order_id',
        'midtrans_status',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }
}