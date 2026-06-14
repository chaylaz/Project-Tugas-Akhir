<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'nama',
        'alamat',
        'no_telepon',
        'paket_layanan_id',
        'status_layanan',
        'masa_aktif',
    ];

    protected $casts = [
        'masa_aktif' => 'date',
    ];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketLayanan::class, 'paket_layanan_id');
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(Tagihan::class, 'pelanggan_id');
    }

    /**
     * Relasi ke user (akun login pelanggan).
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'pelanggan_id');
    }
}
