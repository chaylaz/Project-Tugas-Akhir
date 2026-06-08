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

    public static function generateForPelanggan(Pelanggan $pelanggan, $masaAktif = null): ?self
    {
        if (!$masaAktif) {
            $masaAktif = $pelanggan->masa_aktif;
        }
        $masaAktif = \Carbon\Carbon::parse($masaAktif);

        $indonesianMonths = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $englishMonth = $masaAktif->format('F');
        $year = $masaAktif->format('Y');
        $indonesianMonth = $indonesianMonths[$englishMonth] ?? $englishMonth;
        $periode = "{$indonesianMonth} {$year}";

        $tagihanExist = static::where('pelanggan_id', $pelanggan->id)
            ->where('periode', $periode)
            ->first();

        if (!$tagihanExist) {
            $adminId = \Illuminate\Support\Facades\Auth::id() ?? 1;

            return static::create([
                'pelanggan_id' => $pelanggan->id,
                'paket_layanan_id' => $pelanggan->paket_layanan_id,
                'created_by' => $adminId,
                'periode' => $periode,
                'jumlah' => $pelanggan->paket?->harga ?? 0,
                'due_date' => $masaAktif->toDateString(),
                'status_pembayaran' => 'belum',
            ]);
        }

        return $tagihanExist;
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
                    $newMasaAktif = \Carbon\Carbon::parse($tagihan->due_date)->addMonth();

                    // Jika masa aktif baru masih di masa lalu (atau hari ini), buat tagihan berikutnya
                    if ($newMasaAktif->lessThanOrEqualTo(\Carbon\Carbon::today())) {
                        static::generateForPelanggan($pelanggan, $newMasaAktif);
                    }

                    $masihAda = static::where('pelanggan_id', $tagihan->pelanggan_id)
                        ->where('status_pembayaran', 'belum')
                        ->exists();

                    $pelanggan->update([
                        'status_layanan' => $masihAda ? 'non-aktif' : 'aktif',
                        'masa_aktif' => $newMasaAktif->toDateString(),
                    ]);
                }
            }
        });
    }
}